<?php

namespace Services\Game\Ministry\Commerce ;

class TakeCredits extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function getCity() {
        return $this->city ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::TAKE_CREDITS_MESSAGE($this->city->id, $this->city->getName(), $this->city->credits)) ;
        $form->addInput("amount", new \Quantyl\Form\Fields\Integer(\I18n::AMOUNT())) ;
    }
    
    public function onProceed($data) {
        $me = $this->getCharacter() ;
        $amount = $data["amount"] ;
        
        if ($amount <= 0) {
            throw new \Exception(\I18n::EXP_CANNOT_USE_NEGATIVE_VALUE()) ;
        } else if ($amount > $this->city->credits) {
            throw new \Exception(\I18n::EXP_CITY_DONT_HAVE_ENOUGH_MONEY()) ;
        }
        
        $this->city->addCredits(- $amount) ;
        $me->addCredits($amount) ;
        $me->update() ;
        
        \Model\Game\City\Register::createFromValues(array(
            "character" => $me,
            "city" => $this->city,
            "amount" => - $amount
        )) ;
        
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }

}
