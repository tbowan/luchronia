<?php

namespace Answer\Widget\Misc ;

class Card extends \Quantyl\Answer\Widget {
    
    private $_head ;
    private $_content ;
    private $_classe ;
    
    public function __construct($head = null, $content = null, $add_classe = "") {
        parent::__construct();
        $this->_head = $head ;
        $this->_content = $content ;
        $this->_classe  = $add_classe ;
    }
    
    public function getContent() {
        $classes = $this->getClasses() ;
        $id = $this->getId() ;
        $res = "<div ";
        if ($id != "") {
            $res .= " id=\"$id\"" ;
        }
        $res .= " class=\"{$this->_classe} $classes\"" ;
        $res .= ">";
        
        $res .= "<div class=\"card-head\">" ;
        $res .= "<h3>" . $this->getHead() . "</h3>" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"card-body\">" ;
        $res .= $this->getBody() ;
        $res .= "<div class=\"clear\"></div>" ;
        $res .= "</div>" ;
        
        $res .= "</div>" ;
        return $res ;
    }
    
    public function getClasses() {
        return "card2" ;
    }
    
    public function getId() {
        return "" ;
    }
    
    public function getHead() {
        return $this->_head ;
    }
    
    public function getBody() {
        return $this->_content ;
    }
}
