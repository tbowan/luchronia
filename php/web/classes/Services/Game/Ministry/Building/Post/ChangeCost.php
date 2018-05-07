<?php

namespace Services\Game\Ministry\Building\Post ;

class ChangeCost extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("post", new \Quantyl\Form\Model\Id(\Model\Game\Building\Post::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CHANGE_POST_COST_MESSAGE()) ;
        $form->addInput("cost_mail", new \Quantyl\Form\Fields\Float(\I18n::COST_MAIL()))
             ->setValue($this->post->cost_mail );
        $form->addInput("cost_parcel", new \Quantyl\Form\Fields\Float(\I18n::COST_PARCEL()))
             ->setValue($this->post->cost_parcel );
        return $form ;
    }
    
    public function onProceed($data) {
        
        if ($data["cost_mail"] < 0 || $data["cost_parcel"] < 0) {
            throw new \Exception(\I18n::EXP_TAX_MUST_BE_POSITIVE()) ;
        }
        
        $this->post->cost_mail   = $data["cost_mail"] ;
        $this->post->cost_parcel = $data["cost_parcel"] ;
        $this->post->update() ;
    }

    public function getCity() {
        return $this->post->instance->city ;
    }

    public function getMinistry() {
        return $this->post->instance->job->ministry ;
    }

}
