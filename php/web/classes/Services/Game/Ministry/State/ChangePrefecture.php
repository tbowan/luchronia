<?php

namespace Services\Game\Ministry\State ;

class ChangePrefecture extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city",       new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
        $form->addInput("prefecture", new \Quantyl\Form\Model\Id(\Model\Game\City\Prefecture::getBddTable())) ;
    }

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("State") ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->prefecture->city->equals($this->city)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        } else if ($this->prefecture->distance > $this->prefecture->prefecture->instance->level) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_PREFECTURE_DISTANCE_TO_HIGH()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $city = $this->prefecture->prefecture->instance->city ;
        $form->addMessage(\I18n::STATE_CHANGE_PREFECTURE_MESSAGE(
                new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", $city->getName()),
                100 * $this->prefecture->prefecture->prestige_in,
                100 * $this->prefecture->prefecture->prestige_out        
                )) ;
    }

    public function onProceed($data) {
        $this->city->prefecture = $this->prefecture->prefecture->instance->city ;
        $this->city->update() ;
    }
}
