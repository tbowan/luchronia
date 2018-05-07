<?php

namespace Answer\View\Game\Building ;

use Model\Game\Politic\Ministry;
use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Table;

class TownHall extends Base {
    
    public function getSpecific() {
        $res = "" ;
        $res .= $this->getMinistries() ;
        
        return $res ;
        
    }
    
    public function getMinistries() {
        $system     = \Model\Game\Politic\System::LastFromCity($this->_instance->city) ;
        $government = \Model\Game\Politic\Government::CurrentFromSystem($system) ;
        
        
        $msg = "<ul class=\"itemList\">" ;
        $msg .= $this->dgapAsItem($system) ;
        foreach (Ministry::getAll() as $ministry) {
            $msg .= $this->ministryAsItem($ministry, $government) ;
        }

        $msg .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRIES(),"","",$msg,"") ;
    }
    
    public function dgapAsItem(\Model\Game\Politic\System $system) {
        $msg  = "<li><div class=\"item\">" ;
        $msg .= "<div class=\"icon\">" . \I18n::DGAP_IMAGE() . "</div>" ;
        $msg .= "<div class=\"description\">" ;
        $msg .= "<div class=\"name\">" .  new A("/Game/Ministry/System/Current?city={$this->_instance->city->id}", \I18n::DGAP_NAME()) . "</div>" ;
        $msg .= "<div>" . \I18n::POLITICAL_SYSTEM() . " : " . $system->type->getName() . "</div>" ;
        $msg .= "</div>" ;
        $msg .= "</div></li>" ;
        return $msg ;
    }
    
    public function ministryAsItem(Ministry $m, $g) {
        $msg  = "<li><div class=\"item\">" ;
        $msg .= "<div class=\"icon\">" . $m->getImage() . "</div>" ;
        $msg .= "<div class=\"description\">" ;
        $msg .= "<div class=\"name\">" . new A("/Game/Ministry/?ministry={$m->name}&city={$this->_instance->city->id}", $m->getName()) . "</div>" ;
        $msg .= "<div>" . \I18n::MINISTERS() . " : " . $this->getMinisters($m, $g) . "</div>" ;
        $msg .= "</div>" ;
        $msg .= "</div></li>" ;
        return $msg ;
    }
    
    public function getMinisters(Ministry $m, $g) {
        if ($g === null) {
            return \I18n::NONE() ;
        }
        $ms = array() ;
        foreach (\Model\Game\Politic\Minister::getMinistersForMinistry($g, $m) as $minister) {
            $ms[] = new A("/Game/Character/Show?id={$minister->character->id}", $minister->character->getName()) ;
        }
        if (count($ms) == 0) {
            return \I18n::NONE() ;
        } else {
            return implode(", ", $ms) ;
        }
    }
    
}
