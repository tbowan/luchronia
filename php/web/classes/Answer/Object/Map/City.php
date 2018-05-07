<?php

namespace Answer\Object\Map ;

class City extends \Quantyl\Answer\Answer {
    
    private $_city ;
    
    private $_doc ;
    
    public function __construct(\Model\Game\City $city) {
        parent::__construct();
        $this->_city = $city ;
        
        
        $svg = new \Quantyl\XML\SVG\SVG(360, 180) ;
        
        $svg->setViewPort(0, 0, 1440, 720) ;
        $svg->addChild(new \Quantyl\XML\SVG\Image("/Media/Maps/Moonmap.png", 0, 0, 1440, 720)) ;
        $svg->addChild(new \Quantyl\XML\SVG\Image("/Media/Maps/daynight.png", 0, 0, 1440, 720)) ;
        
        $cx = 720 + $city->longitude * 4 ;
        $abs = $svg->addChild(new \Quantyl\XML\SVG\Line($cx, 0, $cx, 720)) ;
        $abs->setAttribute("style", "stroke:red;stroke-width:5px") ;
        
        $cy = 360 - $city->latitude * 4 ;
        $ord = $svg->addChild(new \Quantyl\XML\SVG\Line(0, $cy, 1440, $cy)) ;
        $ord->setAttribute("style", "stroke:red;stroke-width:5px") ;
        
        $circle = $svg->addChild(new \Quantyl\XML\SVG\Circle($cx, $cy, 10)) ;
        $circle->setAttribute("style", "stroke:red;fill:red;stroke-width:5px") ;
        
        $this->_doc = new \Quantyl\XML\SVG\Document($svg,
                "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">"
                ) ;
    }
    
    public function getContent() {
        return $this->_doc->getContent() ;
        
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        return $this->_doc->sendHeaders($srv) ;
    }
    
    
    
}
