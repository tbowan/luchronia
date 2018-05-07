<?php

namespace Services\User\Edit ;

class Identity extends \Services\Base\Connected {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $user = $this->getUser() ;
        $form->addInput("first_name", new \Quantyl\Form\Fields\Text(\I18n::FIRST_NAME()))
             ->setValue($user->first_name) ;
        $form->addInput("last_name",  new \Quantyl\Form\Fields\Text(\I18n::LAST_NAME()))
             ->setValue($user->last_name) ;
        $form->addInput("sex",        new \Quantyl\Form\Model\EnumSimple(\Model\Enums\Sex::getBddTable(), \I18n::SEX()))
             ->setValue($user->sex) ;
        $form->addInput("birth",      new \Quantyl\Form\Fields\Date(\I18n::BIRTHDAY()))
             ->setValue($user->birth) ;
        $form->addInput("email",      new \Quantyl\Form\Fields\Email(\I18n::EMAIL()))
             ->setValue($user->email) ;
        return $form ;
    }
    
    public function onProceed($data) {
        
        $user = $_SESSION["user"] ;
        $user->first_name = $data["first_name"] ;
        $user->last_name  = $data["last_name"] ;
        $user->sex        = $data["sex"] ;
        $user->birth      = $data["birth"] ;
        $user->email      = $data["email"] ;
        $user->update() ;
        
        return ;
    }

}
