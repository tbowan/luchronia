<?php

namespace Scripts\Cron\Map ;

class All extends \Scripts\Base {
    
    private $_cache_prev ;
    private $_cache_cur ;
    private $_functions ;
    private $_times     ;
    private $_index     ;
    private $_world     ;
    private $_intervals ;
    
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("prefix", new \Quantyl\Form\Fields\Text("", true)) ;
        $form->addInput("index",  new \Quantyl\Form\Fields\Text("", true)) ;
        $form->addInput("running", new \Quantyl\Form\Fields\Boolean()) ;
    }
    
    public function init() {
        parent::init();
        $this->_cache_cur = array() ;
        $this->_cache_prev = array() ;
        $this->_functions  = array ("Albedo", "Monsters", "daynight", "FogOfWar", "Fitness") ;
        foreach ($this->_functions as $v) {
            $this->_times[$v] = 0 ;
        }
        
        $this->_index = \Model\Game\CityIndexMap::CreateFromQtFile($this->index) ;
        $city_temp    = \Model\Game\City::GetById($this->_index->get(0, 0), true) ;
        $this->_world = $city_temp->world ;
        
        $this->_intervals = $this->getIntervals() ;
        
    }
    
    public function getIntervals() {
        $pdo = \Quantyl\Dao\Dal::getPdo() ;
        $st = $pdo->prepare(""
                . " select"
                . "     min(albedo)     as Albedo_min,"
                . "     max(albedo)     as Albedo_max,"
                . "     min(monster_in) as Monsters_min,"
                . "     max(monster_in) as Monsters_max,"
                . "     min(last_seen)  as FogOfWar_min,"
                . "     max(last_seen)  as FogOfWar_max,"
                . "     min(fitness)    as Fitness_min,"
                . "     max(fitness)    as Fitness_max"
                . " from game_city"
                . " where"
                . "     world = :wid") ;
        $st->execute(array("wid" => $this->_world->id)) ;
        $row = $st->fetch(\PDO::FETCH_ASSOC) ;
        
        $res = array() ;
        foreach ($row as $key => $value) {
            $t = explode("_", $key) ;
            if (! isset($res[$t[0]])) {
                $res[$t[0]] = array() ;
            }
            $res[$t[0]][$t[1]] = $value ;
        }
        return $res ;
    }
    
    public function to01($v, $min, $max, $a) {
        
        return $a * ($v - $min) / ($max - $min) ;
    }
    
    public function Albedo($city) {
        $min = $this->_intervals["Albedo"]["min"] ;
        $max = $this->_intervals["Albedo"]["max"] ;
        
        $grey = $this->to01($city->albedo, $min, $max, 255) ;
        return array($grey, $grey, $grey) ;
    }
    
    public function Monsters($city) {
        $min = $this->_intervals["Monsters"]["min"] ;
        $max = $this->_intervals["Monsters"]["max"] ;
        
        $grey = $this->to01($city->monster_out, $min, $max, 255) ;
        return array($grey, $grey, $grey) ;
    }
    
    public function daynight($city) {
        $alpha = $city->sun > 0 ? 127 : 64 ;
        return array(0, 0, 0, $alpha) ;
    }
    
    public function FogOfWar($city) {
        
        $min = $this->_intervals["FogOfWar"]["min"] ;
        $max = $this->_intervals["FogOfWar"]["max"] ;
        
        if ($city->last_seen == null) {
            $alpha = $this->to01($min, $min, $max, 127) ;
        } else {
            $alpha = $this->to01($city->last_seen, $min, $max, 127) ;
        }
        return array(0, 0, 0, $alpha) ;
    }
    
    public function Fitness($city) {
        $min = $this->_intervals["Fitness"]["min"] ;
        $max = $this->_intervals["Fitness"]["max"] ;
        
        $grey = $this->to01($city->fitness, $max, $min, 255) ;
        return array($grey, $grey, $grey) ;
    }
    
    public function getDatas($id) {
        $colors = null ;
        
        if (isset($this->_cache_prev[$id])) {
            $colors = $this->_cache_prev[$id] ;
        }
        
        $miss = true ;
        if (isset($this->_cache_cur[$id])) {
            $colors = $this->_cache_cur[$id] ;
            $miss = false ;
        } else if ($colors  == null) {
            $city = \Model\Game\City::GetById($id, true) ;
            $colors = $this->getColorsForCity($city) ;
        }
        
        if ($miss) {
            $this->_cache_cur[$id]  = $colors ;
        }
        return $colors ;
    }
    
    public function cacheSwap() {
        $this->_cache_prev = $this->_cache_cur ;
        $this->_cache_cur  = array() ;
    }
    
    public function doStuff() {
        
        $index      = $this->_index ;
        $width      = $index->getWidth()  ;
        $height     = $index->getHeight() ;
        $images     = array() ;
        
        foreach ($this->_functions as $key) {
            $image = \Quantyl\Misc\GD\Image::CreateEmpty($width, $height) ;
            $image->setTransparent() ;
            $images[$key] = $image ;
        }
        
        $cnt = new \Quantyl\Misc\Counter($width * $height, 1, $this, "tick") ;
        
        for ($x = 0; $x < $width; $x++) {
            $this->cacheSwap() ;
            for ($y = 0; $y < $height; $y++) {
                $cnt->step() ;
                $this->setAllPixels($index, $x, $y, $images) ;
            }
            if ($this->running) $this->drawImages ($images) ;
        }
        
        $this->drawImages($images) ;
    }

    public function setAllPixels($index, $x, $y, $images) {
        $data = $index->get($x, $y) ;
        if ($data === null || $data == 0) {
            return ;
        }
        
        $colors = $this->getDatas($data) ;
        foreach ($colors as $key => $c) {
            $image = $images[$key] ;
            if (count($c) == 4) { // alpha
                $color = \Quantyl\Misc\GD\Color::FromRGBA($image, $c[0], $c[1], $c[2], $c[3]) ;
            } else if (count($c) == 3) { // RGB
                $color = \Quantyl\Misc\GD\Color::FromRGB($image, $c[0], $c[1], $c[2]) ;
            } else { // Greyscale
                $color = \Quantyl\Misc\GD\Color::FromRGB($image, $c[0], $c[0], $c[0]) ;
            }
            $image->setPixel($x, $y, $color) ;
        }

    }
    
    public function drawImages($images) {
        foreach ($images as $key => $image) {
            $image->writePNG($this->prefix . $key . ".png") ;
        }
        print_r($this->_times) ;
    }
    
    public function tick($cnt) {
        $this->setPercent($cnt->getPercent()) ;
    }
    
    public function getColorsForCity($city) {
        $colors = array() ;
        /*
        $t0 = microtime(true) ;
        $city->read(true) ;
        $tf = microtime(true) ;
        $this->_times["crud"] += $tf - $t0 ;
         */
        $tf = microtime(true) ;
        
        foreach ($this->_functions as $func) {
            $t0 = $tf ;
            $colors[$func] = $this->$func($city) ;
            $tf = microtime(true) ;
            $this->_times[$func] += $tf - $t0 ;
        }
        return $colors ;
    }
    
    
    
}
