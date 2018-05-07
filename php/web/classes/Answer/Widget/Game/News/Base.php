<?php

namespace Answer\Widget\Game\News ;

class Base extends \Quantyl\Answer\Widget {
    
    private $_icon ;
    private $_date ;
    private $_content ;
    
    public function __construct($icon, $date, $content) {
        $this->_icon    = $icon ;
        $this->_date    = $date ;
        $this->_content = $content ;
    }
    
    public function getContent() {
        
        $res = "<div class=\"item\">" ;
        $res .= "<div class=\"icon\">{$this->_icon}</div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"date\">" . $this->_date . "</div>"
                . "<div class=\"message\">" . $this->_content . "</div>"
                . "</div>" ;
        $res .= "</div>" ;
        
        return $res ;
        
    }
    
}
