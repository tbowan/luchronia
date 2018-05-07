<?php

namespace Answer\Widget\Game\City ;

class MapCard extends \Quantyl\Answer\Widget {
    
    private $_c ;
    private $_me ;
    
    public function __construct(\Model\Game\City $c, \Model\Game\Character $me) {
        
        $this->_c = $c ;
        $this->_me = $me ;
    }
    
    public function getContent() {
        
        $msg = "" ;
        $msg .= $this->getGeoInfo($this->_c) ;
        $msg .= $this->getPolInfo($this->_c) ;
        $msg .= $this->getActions($this->_c, $this->_me) ;
        
        return $msg ;
    }
    
    public function getGeoInfo(\Model\Game\City $c) {
        $alt = ( $c->altitude - 96 )*255;
        
        $msg = "<div class=\"col3\">" ;
        $msg .= "<ul class=\"stats\">" ;
        $msg .= "<li><strong>" . \I18n::COORDINATE()       . " :</strong> " . $c->getGeoCoord() . "</li>";
        $msg .= "<li><strong>" . \I18n::ALTITUDE()         . " :</strong> " . $alt . " ". \I18n::METERS() . "</li>";
        // $msg .= "<li><strong>" . \I18n::ALBEDO()           . " :</strong> " . $c->albedo . "</li>";
        $msg .= "<li><strong>" . \I18n::MONSTERS()         . " :</strong> " . round($c->monster_out) . "</li>";
        $msg .= "<li><strong>" . \I18n::BIOME()            . " :</strong> " . new \Quantyl\XML\Html\A("/Help/Biome?id={$c->biome->id}",$c->biome->getName()) . "</li>" ;
        $msg .= "<li><strong>" . \I18n::DAY_OR_NIGHT()     . " :</strong> " . ($c->sun < 0 ? \I18n::NIGHT() : \I18n::DAY()) . "</li>";
        $msg .= "</ul>" ;
        $msg .= "</div>" ;
        
        
        return new \Answer\Widget\Misc\Section(\I18n::MAP_GEO_INFO(), "", "", $msg) ;
    }

    public function getPolInfo(\Model\Game\City $c) {
        $system = \Model\Game\Politic\System::LastFromCity($c) ;
        $buildings = \Model\Game\Building\Instance::CountFromCity($c) ;
        
        $msg  = "<div class=\"col3\">" ;
        $msg .= "<ul class=\"stats\">" ;
        
        $msg .= "<li><strong>" . \I18n::CITY_NAME()        . " :</strong> " . $c->getName() . "</li>" ;
        $msg .= "<li><strong>" . \I18n::POLITICAL_SYSTEM() . " :</strong> " . new \Quantyl\XML\Html\A("/Game/Ministry/System/?system={$system->id}&city={$c->id}", $system->type->getName()) . "</li>" ;
        $msg .= "<li><strong>" . \I18n::BUILDINGS()        . " :</strong> " . $buildings . "</li>" ;
        $msg .= "<li><strong>" . \I18n::POPULATION()       . " :</strong> " . \Model\Game\Character::CountPopulation($c) . " " . new \Quantyl\XML\Html\A("/Game/City/Population?city={$c->id}", \I18n::SEE()) . "</li>" ;
        $msg .= "<li><strong>" . \I18n::CITIZENS()         . " :</strong> " . \Model\Game\Character::CountCitizen($c) . " " . new \Quantyl\XML\Html\A("/Game/City/Citizen?city={$c->id}", \I18n::SEE()) . "</li>" ;
        $msg .= "</ul>" ;
        $msg .= "</div>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::MAP_POL_INFO(), "", "", $msg) ;
    }
    
    public function getActions(\Model\Game\City $c, $me) {
        $msg  = "<div class=\"col3\">" ;
        $msg .= "<ul class=\"stats\">" ;
        $msg .= "<li class=\"button\">" . new \Quantyl\XML\Html\A("/Game/City?id={$c->id}", \I18n::DETAILS()) . "</li>" ;
        
        // Can go there ?
        if (\Model\Game\City\Neighbour::getFromAB($c, $me->position) !== null) {
            foreach (\Model\Game\Skill\Skill::GetByClassname("Move") as $move_skill) {
                $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($me, $move_skill) ;
                $msg .= "<li class=\"button\">" . new \Quantyl\XML\Html\A("/Game/Skill/Outdoor/Move?cs={$cs->id}&city={$me->position->id}&target={$c->id}", \I18n::MOVE()) . "</li>" ;
            }
        }
        
        // Can repatriate there ?
        
        $msg .= "</ul>" ;
        $msg .= "</div>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::MAP_ACTIONS(), "", "", $msg) ;
    }

}
