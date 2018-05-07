<?php

namespace Answer\Widget\Game\City ;

class Social extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\City $c, $classes = "") {
        
        $system = \Model\Game\Politic\System::LastFromCity($c) ;
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY_NAME()        . " :</strong> " . $c->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::POLITICAL_SYSTEM() . " :</strong> " . new \Quantyl\XML\Html\A("/Game/Ministry/System/?system={$system->id}&city={$c->id}", $system->type->getName()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::POPULATION()       . " :</strong> " . \Model\Game\Character::CountPopulation($c) . " " . new \Quantyl\XML\Html\A("/Game/City/Population?city={$c->id}", \I18n::SEE()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::CITIZENS()         . " :</strong> " . \Model\Game\Character::CountCitizen($c) . " " . new \Quantyl\XML\Html\A("/Game/City/Citizen?city={$c->id}", \I18n::SEE()) . "</li>" ;
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::POLITIC(), "", "", $res, $classes) ;
    }
    
}
