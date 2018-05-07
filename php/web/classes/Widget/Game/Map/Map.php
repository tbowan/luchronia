<?php

namespace Widget\Game\Map ;

class Map extends \Quantyl\Answer\Widget {
    
    private $_center ;
    private $_world ;
    private $_character ;
    private $_delta ;
    private $_width ;
    private $_scale ;
    
    private $_u ;
    private $_v ;
    private $_z ;
    
    private $_points ;
    
    public function __construct(
            \Quantyl\Misc\Vertex3D $center,
            \Model\Game\World $world,
            \Model\Game\Character $char,
            $width
            ) {
        
        $this->_center      = $center ;
        $this->_world       = $world ;
        $this->_character   = $char ;
        $this->_width       = $width ;
        
        $this->initGeometry($width) ;
        
        $this->_points = array() ;
        
    }
    
    private function initGeometry($width) {
        
        $this->_scale = 0.4 * pi() / $this->_world->size ;
        $this->_delta = $width * $this->_scale ;
        
        $y = \Quantyl\Misc\Vertex3D::XYZ(0.0, 1.0, 0.0) ;
        
        if ($y->equals($this->_center)) {
            $this->_u = \Quantyl\Misc\Vertex3D::XYZ(1.0, 0.0, 0.0) ;
            $this->_v = \Quantyl\Misc\Vertex3D::XYZ(0.0, 0.0, -1.0) ;
        } else {
            $this->_u = \Quantyl\Misc\Vertex3D::VectorProduct($y, $this->_center) ;
            $this->_u->normalize() ;
            $this->_v = \Quantyl\Misc\Vertex3D::VectorProduct($this->_center, $this->_u) ;
            $this->_v->normalize() ;
        }
    }
    
    private function project(\Quantyl\Misc\Vertex3D $p) {
        return array (
            $this->_u->ScalarProduct($p) * 100.0 / $this->_delta,
            - $this->_v->ScalarProduct($p) * 100.0 / $this->_delta
        ) ;
    }
    
    private function getCityPoint($city) {
        if (! isset($this->_points[$city->id])) {
            $this->_points[$city->id] = $this->project($city->getCoordinate()) ;
        }
        return $this->_points[$city->id] ;
    }
    
    public function getFacePoint($c1, $c2, $c3) {
        
        list($x1, $y1) = $this->getCityPoint($c1) ;
        list($x2, $y2) = $this->getCityPoint($c2) ;
        list($x3, $y3) = $this->getCityPoint($c3) ;
        
        return array (
            intval(($x1 + $x2 + $x3) / 3.0),
            intval(($y1 + $y2 + $y3) / 3.0)
        ) ;
    }
    
    public function getNeighbours(\Model\Game\City $city) {
        $res = array() ;
        foreach (\Model\Game\City\Neighbour::getFromA($city) as $n) {
            $res[] = $n->b ;
        }
        return $res ;
    }
    
    public function getPath(\Model\Game\City $city) {
        $neightbours = $this->getNeighbours($city) ;
        $a = $neightbours[count($neightbours) - 1] ;
        $b = $neightbours[0] ;
        
        $path = new \Quantyl\XML\SVG\Path() ;
        list($x, $y) = $this->getFacePoint($city, $a, $b) ;
        $path->moveTo($x, $y) ;
        
        for ($i = 1; $i < count($neightbours); $i++) {
            $a = $b ;
            $b = $neightbours[$i] ;
            list($x, $y) = $this->getFacePoint($city, $a, $b) ;
            $path->lineTo($x, $y) ;
        }
        $path->close() ;
        return $path ;
        
    }
    
    public function getGroupForCity(\Model\Game\City $city) {
        
        list($x, $y) = $this->getCityPoint($city) ;

        $s = 100 + 10 ;
        if ($x < -$s || $x > $s ||$y < -$s || $y > $s) {
            return null ;
        }
        $g = new \Quantyl\XML\SVG\G() ;
        $g->setAttribute("id", $city->id) ;
        $g->setAttribute("onclick", "map_click(this)") ;
        
        $path = $this->getPath($city) ;
        if ($this->_character->exists() && \Model\Stats\Game\Moves::GetFromCharacterCity($this->_character, $city) === null) {
            $grey = 256 - $city->albedo ;
            $path->setStyle("stroke:black;fill:rgb($grey, $grey, $grey);") ;
        } else if ($city->sun > 0) {
            $path->setStyle("stroke:black;fill:" . $city->biome->day_color . ";") ;
        } else {
            $path->setStyle("stroke:black;fill:" . $city->biome->night_color . ";") ;
        }
        
        $g->addChild($path) ;

        /*
        if ($this->_character->exists() && $city->equals($this->_character->position)) {
            $g->addChild(new \Quantyl\XML\SVG\Text($x, $y, "#"))
              ->setStyle("font-size:" . (50 / $this->_width) . "px");
        } else if ($city->name != "") {
            $g->addChild(new \Quantyl\XML\SVG\Text($x, $y, "*"))
              ->setStyle("font-size:" . (50 / $this->_width) . "px") ;
        }
        */
        
        return $g ;
    }
    
    public function getContent() {
        $svg = new \Quantyl\XML\SVG\SVG(480, 480) ;
        $svg->setViewPort(0, 0, 200, 200) ;
        $g = $svg->addChild(new \Quantyl\XML\SVG\G()) ;
        $g->setAttribute("transform", "translate(100, 100)") ;
        
        foreach (\Model\Game\City::GetInBall($this->_world, $this->_center, 1.5 * $this->_delta) as $city) {
            $gc = $this->getGroupForCity($city) ;
            if ($gc !== null) {
                $g->addChild($gc) ;
            }
        }
        
        $rect = $g->addChild(new \Quantyl\XML\SVG\Rect(200, 200)) ;
        $rect->setPos(-100, -100) ;
        $rect->setAttribute("style", "stroke:red;fill:none;") ;
        
        
        $res  = "<div id=\"map-svg\">" ;
        $res .= "<script src=\"/js/map.js\"></script>" ;
        $res .= $svg ;
        $res .= "</div>" ;
        $res .= "<div id=\"map-html\">" ;
        $res .= "</div>" ;
        $res .= "<div class=\"clear\"></div>" ;
        
        return $res ;
    }
    
}
