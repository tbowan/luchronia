<?php

namespace Answer\Widget\Game\Ministry\Dgap ;

class Government extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Politic\System $system, \Model\Game\Character $viewer, $classes = "") {
        
        $more = "" ;
        if ($system->canManage($viewer)) {
            $more .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/Create?system={$system->id}", \I18n::CREATE()) ;
            $more .= ", " ;
            $more .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/Mine", \I18n::GOVERNMENT_MINE()) ;
        }
        
        parent::__construct(\I18n::GOVERNMENT(), $more, "", $this->getGovernment($system), $classes);
    }
    
    public function getGovernment(\Model\Game\Politic\System $s) {
        
        $res = "" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/Log?city={$s->city->id}", \I18n::GOVERNMENT_LOG()) ;
        
        $gov = \Model\Game\Politic\Government::CurrentFromSystem($s) ;
        if ($gov == null) {
            $res .= \I18n::NO_GOVERNMENT() ;
        } else {
            $res .= "<ul class=\"itemList\">" ;
            foreach (\Model\Game\Politic\Minister::getMinisterCharacter($gov) as $char) {
                $res .= "<li><div class=\"item\">" ;
                $res .= "<div class=\"icon\">" . $char->getImage("mini") . "</div>" ;
                $res .= "<div class=\"description\">" ;
                $res .= "<div class=\"name\">" . $char->getName() . " (" . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", \I18n::DETAILS()) . ")</div>" ;
                $res .= "<div>"
                    . $char->race->getName() . " "
                    . $char->sex->getName() . " "
                    . \I18n::LEVEL() . " " . $char->level
                    . "</div>" ;

                $res .= "<div>" ;
                foreach (\Model\Game\Politic\Minister::getMinistries($gov, $char) as $m) {
                    $res .= new \Quantyl\XML\Html\A("/Game/Ministry/?ministry={$m->name}&city={$s->city->id}", $m->getImage("icone-inline")) ;
                }
                $res .= "</div>" ;
                $res .= "</div>" ; // description
                $res .= "</div></li>" ;
            }
            $res .= "</ul>" ;
        }
        return $res ;
    }
    
    
}
