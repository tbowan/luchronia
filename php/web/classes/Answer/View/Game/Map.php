<?php

namespace Answer\View\Game ;

class Map extends \Answer\Decorator\Game {
    
    private $_center ;
    private $_me ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\Game\City $position, \Model\Game\Character $me) {
        parent::__construct($service, "");
        $this->_center = $position ;
        $this->_me = $me ;
    }
    
    public function getTplMeta() {
        $res  = parent::getTplMeta();
        $res .= "<script src=\"/js/map.js\"></script>" ;
        $res .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/style/css/map.css\" />" ;
        return $res ;
    }
    
    public function getTplContent() {
        return ""
                . $this->getMap()
                . $this->getInformation()
                ;
    }

    public function getTplTitle() {
        return \I18n::MAP_TITLE() ;
    }
    
    
    public function getMap() {
        
        $svg = new \Quantyl\XML\SVG\SVG(500, 500) ;
        $svg->setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink") ;
        $svg->setAttribute("class", "map") ;
        $svg->setViewPort(0, 0, 200, 200) ;
        $svg->setAttribute("onload", "map_loaded({$this->_center->id}, {$this->_center->world->size});") ;
        
        $svg->addDef(new \Quantyl\XML\SVG\Image("/Media/icones/Map/has_townhall.png", -5, -5, 10, 10), "has_townhall") ;
        $svg->addDef(new \Quantyl\XML\SVG\Image("/Media/icones/Map/has_ruin.png",     -5, -5, 10, 10), "has_ruin") ;
        $svg->addDef(new \Quantyl\XML\SVG\Image("/Media/icones/Map/has_friend.png",   -5, -5, 10, 10), "has_friend") ;
        $svg->addDef(new \Quantyl\XML\SVG\Image("/Media/icones/Map/Here.png",         -5, -5, 10, 10), "here") ;
        $svg->addDef(new \Quantyl\XML\SVG\Image("/Media/icones/Map/There.png",        -5, -5, 10, 10), "there") ;
                
        $g = $svg->addChild(new \Quantyl\XML\SVG\G()) ;
        $g->setAttribute("transform", "translate(100, 100)") ;
        $g->setAttribute("id", "map-viewport") ;
        
        return new \Answer\Widget\Misc\Section(\I18n::MAP(), "", "", $svg, "card-2-3") ;
    }
    
    public function getInformation() {
        $res = "<div id=\"map-information\" class=\"card-1-3\">" ;
        $res .= new \Answer\Widget\Game\City\MapCard($this->_center, $this->_me) ;
        $res .= "</div>" ;
        return $res ;
    }
    
    
}
