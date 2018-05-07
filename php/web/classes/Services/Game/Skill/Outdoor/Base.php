<?php

namespace Services\Game\Skill\Outdoor ;

abstract class Base extends \Services\Game\Skill\BaseExec {
    
    public function doStuff($amount, $data) {
        $city = $this->getCity() ;
        
        return "<p>"
                . new \Quantyl\XML\Html\A("/Game/Building/OutSide?city={$city->id}", \I18n::SKILL_BACK_BUILDING())
                . "</p>" ;
    }
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        parent::fillParamForm($form) ;
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->cs->skill->building_job->equals(\Model\Game\Building\Job::GetByName("Outside"))) {
            // Player is cheating
            throw new \Exception() ;
        }
    }

    public function getCity() {
        return $this->city ;
    }
    
    public function getBuildingInfo() {
        
        $job = \Model\Game\Building\Job::GetByName("OutSide") ;
        
        $res = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" ;
        $res .= new \Quantyl\XML\Html\Img("/Media/icones/Model/Building/OutSide.png", $job->getName(), "icone-med") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"description\">" ;
        $res .= "<div class=\"name\">" . \I18n::BUILDING() . " : " . new \Quantyl\XML\Html\A("/Game/Building/OutSide?city={$this->_city->id}", $job->getName()) . "</div>" ;
        $res .= "<div class=\"monsters\">" . \I18n::MONSTERS() . " : " . number_format($this->city->monster_out) . "</div>" ;
        $res .= "</div>" ;
        
        $res .= "</div></li>" ;
        
        return $res ;
    }
    
    
    public function getJob() {
        return \Model\Game\Building\Job::GetByName("Outside");
    }


}
