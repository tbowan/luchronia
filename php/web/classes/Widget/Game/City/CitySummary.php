<?php

namespace Widget\Game\City ;

class CitySummary extends \Quantyl\Answer\Widget {
    
    private $_city ;
    private $_character ;
    
    public function __construct(\Model\Game\City $city, \Model\Game\Character $char) {
        $this->_city = $city ;
        $this->_character = $char ;
    }
    
    
    
    public function getContent() {
        $name = $this->_city->getName() ;
        
        $system = \Model\Game\Politic\System::LastFromCity($this->_city) ;
        $system_msg = new \Quantyl\XML\Html\A("/Game/Ministry/System/?system={$system->id}", $system->type->getName()) ;
        
        $res  = "<h2>" . \I18n::CITY_SUMMARY() . "</h2>";
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY_NAME()        . " :</strong> {$name} </li>" ;
        $res .= "<li><strong>" . \I18n::COORDINATE()       . " :</strong> " . $this->_city->getGeoCoord() . "</li>" ;
        
        if ($this->_city->sun < 0) {
            $res .= "<li><strong>" . \I18n::DAY_OR_NIGHT()     . " :</strong> " . \I18n::NIGHT() . "</li>" ;
            $res .= "<li><strong>" . \I18n::MONSTERS()         . " :</strong> " . floor($this->_city->monster_out) . "</li>" ;
            $res .= "<li><strong>" . \I18n::NEXT_SUNRISE()     . " :</strong> " . \I18n::_date_time($this->_city->sunrise - DT) . "</li>" ;
            $res .= "<li><strong>" . \I18n::NEXT_SUNSET()      . " :</strong> " . \I18n::_date_time($this->_city->sunset - DT) . "</li>" ;
        } else {
            $res .= "<li><strong>" . \I18n::DAY_OR_NIGHT()     . " :</strong> " . \I18n::DAY() . "</li>" ;
            $res .= "<li><strong>" . \I18n::MONSTERS()         . " :</strong> " . floor($this->_city->monster_out) . "</li>" ;
            $res .= "<li><strong>" . \I18n::NEXT_SUNSET()      . " :</strong> " . \I18n::_date_time($this->_city->sunset - DT) . "</li>" ;
            $res .= "<li><strong>" . \I18n::NEXT_SUNRISE()     . " :</strong> " . \I18n::_date_time($this->_city->sunrise - DT) . "</li>" ;
        }
        $res .= "<li><strong>" . \I18n::POLITICAL_SYSTEM() . " :</strong> " . $system_msg . " " . \I18n::HELP("/Help/Politic/System?id={$system->type->getId()}") . "</li>" ;
        $res .= "<li><strong>" . \I18n::BIOME()            . " :</strong> " . $this->_city->biome->getName() . " " . \I18n::HELP("/Help/Biome?id={$this->_city->biome->id}") . "</li>" ;
        $res .= "<li><strong>" . \I18n::POPULATION()       . " :</strong> " . \Model\Game\Character::CountPopulation($this->_city) . " " . new \Quantyl\XML\Html\A("/Game/City/Population?city={$this->_city->id}", \I18n::SEE()) . "</li>" ;
        $res .= "</ul>" ;
        
        if (\Model\Game\City\Neighbour::getFromAB($this->_city, $this->_character->position) !== null) {
            $walk = \Model\Game\Skill\Skill::GetByName("Walk") ;
            $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->_character, $walk) ;
            
            if ($cs !== null) {
                $res .= new \Quantyl\XML\Html\A("/Game/Skill/Outdoor/Move?cs={$cs->id}&city={$this->_character->position->id}&target={$this->_city->id}", $walk->getName()) ;
            }
        }
        
        return "$res" ;
        
    }
    
    
}
