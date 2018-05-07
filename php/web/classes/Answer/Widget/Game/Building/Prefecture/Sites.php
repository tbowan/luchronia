<?php

namespace Answer\Widget\Game\Building\Prefecture ;

class Sites extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Prefecture $pref, $classes = "") {
        
        $res = "" ;
        
                
        foreach (\Model\Game\Building\Site::GetForPrefecture($pref) as $site) {
            $inst = $site->instance ;
            
            $res .= "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $inst->getImage() . "</div>" ;
            $res .= "<div class=\"description\">"
                    . "<div class=\"name\">" . $inst->getName() . "</div>"
                    . "<div class=\"city\">" . \I18n::POSITION() . " : " . new \Quantyl\XML\Html\A("/Game/City?id={$inst->city->id}", $inst->city->getName()) . "</div>"
                    . "<div class=\"health\">" . \I18n::HEALTH() . " : " . new \Quantyl\XML\Html\Meter(0, $inst->getMaxHealth(), $inst->health) . "</div>"
                    . "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Building/?id={$inst->id}", \I18n::DETAILS()) . new \Quantyl\XML\Html\A("/Game/Ministry/Building/Prefecture/Provide?site={$site->id}&prefecture={$pref->id}", \I18n::GRANT_NEEDED()) . "</div>"
                    . "</div>" ;
            $res .= "</div></li>" ;
        }
        
        if ($res == "") {
            $msg = \I18n::NO_BUILDING_SITE() ;
        } else {
            $msg = "<ul class=\"itemList\">$res</ul>" ;
        }
        
        parent::__construct(\I18n::PREFECTURE_SITE_BELONGING(), "", "", $msg, $classes);
    }
    
    
    private function getBuildings(\Model\Game\City $c) {
        $res = "" ;
        foreach (\Model\Game\Building\Instance::GetFromCity($c) as $i) {
            $res .= $i->getImage("icone-inline") ;
        }
        return $res ;
    }
    
}
