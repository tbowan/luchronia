<?php

namespace Answer\Widget\Menu ;

class Avatar extends \Quantyl\Answer\Widget {
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $char) {
        parent::__construct();
        $this->_char = $char ;
    }
    
    public function getContent() {
        
        $res = "" ;
        
        $res .= "<div class=\"avatar\">" . $this->_char->getImage("mini", "avatar-tiny") . "</div>" ;
        
        $res .= "<div class=\"informations\">" ;
        $res .= "<p class=\"char_name\">" . $this->_char->getName() . "</p>" ;
        $res .= "<p class=\"char_info\">" ;
        $res .= $this->_char->race->getName() . " " ;
        $res .= $this->_char->sex->getName() . " " ;
        $res .= \I18n::LVL() . " " . $this->_char->level ;
        $res .= "</p>" ;
        
        $res .= "<ul>" ;
        $res .= "<li>" . \I18n::TIME_ICO()      
                . "<span>"
                    . " <meter value=\"".$this->_char->getTime()
                    . "\" min=\"0\""
                    . " max=\"". $this->_char->getMaxTime() ."\">"
                    . $this->_char->getTime(). " / " . $this->_char->getMaxTime()
                    . "</meter>"
                    . "</span>"
                .  "</li>" ;
        $res .= "<li>" . \I18n::CREDITS_ICO()   
                . "<span>"
                . $this->_char->getCredits()
                . "</span>"
                . "</li>" ;
        $res .= "</ul>" ;
        $res .= "</div>" ;

        
        return $res ;

        
    }
    
}
