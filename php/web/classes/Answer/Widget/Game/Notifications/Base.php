<?php

namespace Answer\Widget\Game\Notifications;

class Base extends \Quantyl\Answer\Widget {
    
    private $_title ;
    private $_content ;
    private $_more ;
    private $_class ;
    
    public function __construct($title, $content, $class = "", $more = null) {
        parent::__construct() ;
        $this->_title   = $title ;
        $this->_content = $content ;
        $this->_more    = $more ;
        $this->_class   = $class ;
    }
    
    public function getContent() {
        
        $res  = "<div class=\"notification card-1-3 {$this->_class}\">" ;
        $res .= "<h2>{$this->_title}</h2>" ;
        $res .= "<div class=\"content\">{$this->_content}</div>" ;
        if ($this->_more !== null) {
            $res .= "<div class=\"more\">{$this->_more}</div>" ;
        }
        $res .= "</div>" ;
        
        return $res ;
    }
    
}
