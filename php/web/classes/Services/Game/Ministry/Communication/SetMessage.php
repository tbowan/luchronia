<?php

namespace Services\Game\Ministry\Communication ;

class SetMessage extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("townhall", new \Quantyl\Form\Model\Id(\Model\Game\Building\TownHall::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE()))
             ->setValue($this->townhall->welcome);
    }
    
    public function onProceed($data) {
        $this->townhall->welcome = $data["message"] ;
        $this->townhall->update() ;
    }

    public function getCity() {
        return $this->townhall->instance->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Communication") ;
    }

}
