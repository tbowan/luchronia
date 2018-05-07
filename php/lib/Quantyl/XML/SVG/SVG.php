<?php

namespace Quantyl\XML\SVG;

/**
 * Description of SVG
 *
 * @author henin
 */
class SVG extends \Quantyl\XML\Base
{
    
    private $_defs ;

    public function sendHeaders(\Quantyl\Server\Server $srv) {
        $srv->header("Content-type: image/svg+xml") ;
    }
        
    public function isDecorable() {
        return false ;
    }
    
    /**
     * Construit un svg vide.
     */
    public function __construct($width = "", $height = "")
    {
        parent::__construct("svg");
        $this->setAttribute("xmlns", "http://www.w3.org/2000/svg");
        $this->setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink") ;
        $this->setAttribute("version", "1.1");
        
        if ($width != "") {
            $this->setAttribute("width", $width);
        }
        if ($height != "") {
            $this->setAttribute("height", $height);
        }
        
        $this->_defs = null ;
        
    }
    
    public function addDef($svg_element, $id = null) {
        if ($this->_defs == null) {
            $this->_defs = $this->addChild(new Defs()) ;
        }
        
        if ($id != null) {
            $svg_element->setAttribute("id", $id) ;
        }
        
        return $this->_defs->addChild($svg_element) ;
        
    }
    

    public function setViewPort($x0, $y0, $x1, $y1) {
        $this->setAttribute("viewBox", "$x0 $y0 " . ($x1 - $x0) . " " . ($y1 - $y0)) ;
    }
}

?>
