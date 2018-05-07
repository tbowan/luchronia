<?php

namespace Widget\Game\Map ;

class InfoUnseen extends \Quantyl\Answer\Widget {
    
    private $_city ;
    private $_character ;
    
    public function __construct(\Model\Game\City $c, \Model\Game\Character $char) {
        $this->_city = $c ;
        $this->_character = $char ;
    }
    
    public function getContent() {
        $name = $this->_city->name ;
        
        $res  = "<h2>" . \I18n::CITY_SUMMARY() . "</h2>";
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY_NAME()        . \I18n::HELP("/Wiki/Nom de la ville") . " :</strong> {$name} </li>" ;
        $res .= "<li><strong>" . \I18n::COORDINATE()       . \I18n::HELP("/Wiki/Coordonnées") . " :</strong> " . $this->_city->getGeoCoord() . "</li>" ;
        $res .= "</ul>" ;
        
        if (\Model\Game\City\Neighbour::getFromAB($this->_city, $this->_character->position) !== null) {
            $walk = \Model\Game\Skill\Skill::GetByName("Walk") ;
            $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->_character, $walk) ;
            if ($cs !== null) {
                $res .= new \Quantyl\XML\Html\A("/Game/Skill/Outdoor/Move?cs={$cs->id}&city={$this->_character->position->id}&target={$this->_city->id}", $walk->getName()) ;
            }
        }
        
        $res .= new \Quantyl\XML\Html\A("/Game/Map/?city={$this->_city->id}", \I18n::MAP_CENTER_HERE()) ;
        
        return "$res" ;
    }
    
    public function isDecorable() {
        return false ;
    }
    
    
}