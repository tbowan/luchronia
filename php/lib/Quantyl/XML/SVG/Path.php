<?php

namespace Quantyl\XML\SVG ;

class Path extends Shape
{
    
    private $_path ;
    
    public function __construct()
    {
        parent::__construct("path") ;
        $this->init() ;
    }
    
    public function init() {
        $this->_path = "" ;
    }
    
    public function moveTo($x, $y) {
        $this->_path .= "M$x $y " ;
    }
    
    public function lineTo($x, $y) {
        $this->_path .= "L$x $y " ;
    }
    
    public function close() {
        $this->_path .= "Z" ;
    }
    
    public function getXml() {
        
        $this->setAttribute("d", $this->_path) ;
        return parent::getXml() ;
    }
}

?>