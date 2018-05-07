<?php

namespace Answer\View\Help\Characteristic ;

class ShowAll extends \Answer\View\Base {
    
    private $_viewer ;
    
    public function __construct($service, $viewer) {
        parent::__construct($service);
        $this->_viewer = $viewer ;
    }
    
    public function getTplContent() {
        
        return ""
                . $this->getAllMessage()
                . $this->getPrimary("card-1-2")
                . $this->getSecondary("card-1-2")
                ;
    }
    
    public function getAllMessage($classes = "") {
        return new \Answer\Widget\Misc\Section($this->getTplTitle(), "", "", \I18n::HELP_ALLCHARACTERISTIC_MESSAGE(), $classes) ;
    }
    
    public function getTable($characteristics) {
        $res = "<ul class=\"itemList\">" ;
        foreach ($characteristics as $b) {
            $res .= "<li class=\"card-1-2\"><div class=\"item\">"
                    . "<div class=\"icon\">". $b->getImage("icone") . "</div>"
                    . "<div class=\"description\">"
                        . "<div class=\"name\">" . ucfirst($b->getName()) . "</div>"
                        . $this->getCharValue($b)
                        . "<div class=\"links\">" . \I18n::HELP_MSG("/Help/Characteristic?id={$b->id}") . "</div>"
                    . "</div>"
                    . "</div></li>" ;
        }
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getCharValue(\Model\Game\Characteristic $c) {
        if ($this->_viewer === null) {
            return "" ;
        } else {
            $value = \Model\Game\Characteristic\Character::getValue($this->_viewer, $c) ;
            return "<div>" . \I18n::VALUE() . " : " . $value . "</div>" ;
        }
    }
    
    public function getPrimary($classes = "") {
        $res = "" ;
        $res .= \I18n::PRIMARY_CHARACTERISTICS_HELP_MESSAGE() ;
        $res .= $this->getTable(\Model\Game\Characteristic::GetPrimary()) ;
        
        return new \Answer\Widget\Misc\Section(\I18n::PRIMARY_CHARACTERISTICS(), "", "", $res, $classes) ;
    }
    
    public function getSecondary($classes = "") {
        $res = "" ;
        $res .= \I18n::PRIMARY_CHARACTERISTICS_HELP_MESSAGE() ;
        $res .= $this->getTable(\Model\Game\Characteristic::GetSecondary()) ;
                
        return new \Answer\Widget\Misc\Section(\I18n::SECONDARY_CHARACTERISTICS(), "", "", $res, $classes) ;
    }
    
}
