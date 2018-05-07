<?php

namespace Services\Game\Skill\Indoor ;

use Model\Game\Building\Instance;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

abstract class Base extends \Services\Game\Skill\BaseExec {

    public function fillParamForm(Form &$form) {
        parent::fillParamForm($form) ;
        $form->addInput("inst", new Id(Instance::getBddTable(), "", false)) ;
    }
    
    public function getCity() {
        return $this->inst->city ;
    }
    
    public function getJob() {
        return $this->inst->job ;
    }
    
    public function getBuildingInfo() {
        $i = $this->inst ;
        
        $msg  = "<li><div class=\"item\">" ;
        $msg .= "<div class=\"icon\">" ;
        $msg .= $i->getImage("full") ;
        $msg .= "</div>" ;
        
        $msg .= "<div class=\"description\">" ;
        $msg .= "<div class=\"name\">"
                    . \I18n::BUILDING() . " : "
                    . new \Quantyl\XML\Html\A("/Game/Building/?id={$i->id}", $i->getName()) 
                    . "</div>" ;
        $msg .= "<div class=\"type\">" 
                    . $i->type->getName() 
                    . " " . \I18n::LEVEL() . " " . $i->level
                    . "</div>" ;
        
        $msg .= "<div class=\"health\">"
                    . \I18n::HEALTH_ICO() . " " . new \Quantyl\XML\Html\Meter(0, $i->getMaxHealth(), $i->health)
                    . \I18n::WEAR_ICO()   . " " . new \Quantyl\XML\Html\Meter(0, $i->health, $i->barricade)
                    . "</div>" ;
        
        $msg .= "</div>" ;
        $msg .= "</div></li>" ;
        return $msg ;
        
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        if (! $this->getCity()->canEnter($this->getCharacter()) ) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_WALL_AVOIDED()) ;
        }
        
        $skill = $this->cs->skill ;
        if ($skill->building_job !== null && ! $skill->building_job->equals($this->inst->job) ) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if ($skill->building_type !== null && ! $skill->building_type->equals($this->inst->type) ) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
    }
    
    public function doStuff($points, $data) {
        $inst = $this->inst ;
        
        return "<p>"
                . new \Quantyl\XML\Html\A("/Game/Building/?id={$inst->id}", \I18n::SKILL_BACK_BUILDING())
                . "</p>" ;
    }
    
}
