<?php

namespace Services\Game\Ministry\Defense ;

class ChangeWallMessage extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("wall", new \Quantyl\Form\Model\Id(\Model\Game\Building\Wall::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
    }
    
    public function onProceed($data) {
        $this->wall->message = $data["message"] ;
        $this->wall->update() ;
    }

    public function getCity() {
        return $this->wall->instance->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Defense") ;
    }
    
    

}
