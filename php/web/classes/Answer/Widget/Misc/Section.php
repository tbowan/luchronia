<?php

namespace Answer\Widget\Misc ;

class Section extends \Quantyl\Answer\Widget {
    
    private $_head ;
    private $_more ;
    private $_meta ;
    
    private $_content ;
    private $_classe ;
    
    public function __construct(
            $head = null,
            $more = null,
            $meta = null,
            $content = null,
            $classes = "") {
        parent::__construct();
        $this->_head    = $head ;
        $this->_more    = $more ;
        $this->_meta    = $meta ;
        $this->_content = $content ;
        $this->_classe  = "section $classes" ;
    }
    
    public function openDiv() {
        $classes = $this->getClasses() ;
        $id = $this->getId() ;
        
        $res = "<div ";
        if ($id != "") {
            $res .= " id=\"$id\"" ;
        }
        $res .= " class=\"{$this->_classe} $classes\"" ;
        $res .= ">";
        $res .= "<div class=\"wrapper\">" ;
        return $res ;
    }
    
    public function closeDiv() {
        $res  = "</div>" ; // Wrapper
        $res .= "</div>" ; // Section
        return $res ;
    }
    
    public function makeMore() {
        $res = "" ;
         if ($this->_more != null) {
            $res .= "<span class=\"more\">" ;
            $res .= $this->_more ;
            $res .= "</span>" ;
        }
        return $res ;
    }
    
    public function makeTitle() {
        $res = "" ;
         if ($this->_head != null) {
            $res .= "<h1 class=\"section-title\">" ;
            $res .= $this->_head ;
            $res .= "</h1>" ;
        }
        return $res ;
    }
    
    public function makeMeta() {
        $res = "" ;
         if ($this->_meta != null) {
            $res .= "<span class=\"meta\">" ;
            $res .= $this->_meta ;
            $res .= "</span>" ;
        }
        return $res ;
    }
    
    public function makeHead() {
        return "<div class=\"section-head\">"
                . $this->makeTitle()
                . $this->makeMore()
                . "</div>"
                . $this->makeMeta()
                        ;
                
    }
    
    public function makeContent() {
        $res  = "<div class=\"section-content\">" ;
        $res .= $this->getBody() ;
        $res .= "<div class=\"clear\"></div>" ;
        $res .= "</div>" ;
        return $res ;
    }
    
    public function getContent() {
        return ""
                . $this->openDiv() 
                . $this->makeHead()
                . $this->makeContent()
                . $this->closeDiv() ;

    }
    
    public function getClasses() {
        return "" ;
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
