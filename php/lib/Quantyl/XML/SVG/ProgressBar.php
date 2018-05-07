<?php

namespace Quantyl\XML\SVG;

/**
 * Un rectangle en SVG
 *
 * @author henin
 */
class ProgressBar extends SVG {

    public function __construct($level, $max) {
    
        parent::__construct(100,"1.2em") ;
        
        $pc = intval(100 * $level / $max) ;
        
        $this->addChild(new Rect("$pc%", "100%", "10", "10"))
             ->setStyle("stroke: none; fill: #70a6ad") ;
        $this->addChild(new Rect("100%", "100%", "10", "10"))
             ->setStyle("stroke: #a4c1b8;fill: #b2c1bc;stroke-width:2; fill-opacity:0.2");
        
        
        $this->addChild(new \XML\SVG\Text("90", "1em", $level ))
             ->setStyle("text-anchor:end;fill:#E8E1D1");
        
        
    }

    public static function makeString($level, $max) {
        $temp = new ProgressBar($level, $max) ;
        return $temp->getXml() ;
    }
}

?>
