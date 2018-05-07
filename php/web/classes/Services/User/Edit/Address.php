<?php

namespace Services\User\Edit ;

class Address extends \Services\Base\Connected {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $user = $this->getUser() ;
        $form->addInput("address",       new \Quantyl\Form\Fields\Text(\I18n::ADDRESS()))
             ->setValue($user->address) ;
        $form->addInput("address_compl", new \Quantyl\Form\Fields\Text(\I18n::ADDRESS_COMPL()))
             ->setValue($user->address_compl) ;
        $form->addInput("code",          new \Quantyl\Form\Fields\Text(\I18n::CITY_CODE()))
             ->setValue($user->code) ;
        $form->addInput("city",          new \Quantyl\Form\Fields\Text(\I18n::CITY_NAME()))
             ->setValue($user->city) ;
        $form->addInput("state",         new \Quantyl\Form\Fields\Text(\I18n::STATE()))
             ->setValue($user->state) ;
        return $form ;
    }
    
    public function onProceed($data) {
        
        $user = $_SESSION["user"] ;
        $user->address       = $data["address"] ;
        $user->address_compl = $data["address_compl"] ;
        $user->code          = $data["code"] ;
        $user->city          = $data["city"] ;
        $user->state         = $data["state"] ;
        $user->update() ;
        
        return ;
    }
    
}
