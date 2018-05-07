<?php

namespace Scripts\Cron\Map ;

abstract class Base extends \Scripts\Base {
    
    private $_cache_prev ;
    private $_cache_cur ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("index", new \Quantyl\Form\Fields\Text("", true)) ;
        $form->addInput("file", new \Quantyl\Form\Fields\Text("", true)) ;
        $form->addInput("running", new \Quantyl\Form\Fields\Boolean()) ;
    }
    
    public function init() {
        parent::init();
        $this->_cache_cur = array() ;
        $this->_cache_prev = array() ;
    }
    
    public function getRGB($id) {
        $color = null ;
        
        if (isset($this->_cache_prev[$id])) {
            $color = $this->_cache_prev[$id] ;
        }
        
        $miss = true ;
        if (isset($this->_cache_cur[$id])) {
            $color = $this->_cache_cur[$id] ;
            $miss = false ;
        } else if ($color  == null) {
            $city = \Model\Game\City::GetById($id) ;
            $color = $this->getRgbByCity($city) ;
        }
        
        if ($miss) {
            $this->_cache_cur[$id]  = $color ;
        }
        return $color ;
    }
    
    public function cacheSwap() {
        $this->_cache_prev = $this->_cache_cur ;
        $this->_cache_cur  = array() ;
    }
    
    
    public function doStuff() {
        
        $map = \Model\Game\CityIndexMap::CreateFromQtFile($this->index) ;
        
        $width  = $map->getWidth()  ;
        $height = $map->getHeight() ;
        
        $cnt = new \Quantyl\Misc\Counter($width * $height, 1, $this, "tick") ;
        $image = \Quantyl\Misc\GD\Image::CreateEmpty($width, $height) ;
        $image->setTransparent() ;
        
        for ($x = 0; $x < $width; $x++) {
            $this->cacheSwap() ;
            for ($y = 0; $y < $height; $y++) {
                $cnt->step() ;
                
                $data = $map->get($x, $y) ;
                if ($data === null || $data == 0) {
                    continue ;
                } else {
                    $c = $this->getRGB($data) ;
                    $color = \Quantyl\Misc\GD\Color::FromRGB($image, $c[0], $c[1], $c[2]) ;
                    $image->setPixel($x, $y, $color) ;
                }
            }
            if ($this->running) {
                $image->writePNG($this->file) ;
            }
        }
        $image->writePNG($this->file) ;
    }
        
    public function tick($cnt) {
        $this->setPercent($cnt->getPercent()) ;
    }
        
    public abstract function getRgbByCity($city) ;
    
}
