<?php

namespace Answer\Widget\Game\Building\Prefecture ;

class Cities extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Prefecture $pref, $classes = "") {
        
        $res  = \I18n::PRESTIGE_TAX_MESSAGE(100 * $pref->prestige_in, 100 * $pref->prestige_out) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Prefecture/ChangeTax?prefecture={$pref->id}", \I18n::CHANGE_TAX()) ;
        
        $res .= "<ul class=\"itemList\">" ;
                
        foreach (\Model\Game\City::GetFromPrefecture($pref->instance->city) as $city) {
            
            $res .= "<li><div class=\"item\">"
                    . "<div class=\"name\">" . $city->getName() . "</div>"
                    . "<div class=\"population\">" . \I18n::POPULATION() . " : " . \Model\Game\Character::CountPopulation($city) . " / " . \Model\Game\Character::CountCitizen($city) . "</div>"
                    . "<div class=\"building\">" . \I18n::BUILDINGS() . " : " . $this->getBuildings($city) . "</div>"
                    . "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/City/?id={$city->id}", \I18n::SEE()) . "</div>"
                     ;
            $res .= "</div></li>" ;
        }
        $res .= "</ul>" ;
        
        
        parent::__construct(\I18n::PREFECTURE_CITY_BELONGING(), "", "", $res, $classes);
    }
    
    
    private function getBuildings(\Model\Game\City $c) {
        $res = "" ;
        foreach (\Model\Game\Building\Instance::GetFromCity($c) as $i) {
            $res .= $i->getImage("icone-inline") ;
        }
        return $res ;
    }
    
}
