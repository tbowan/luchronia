<?php

namespace Services\User\Mail ;

class Set extends \Services\Base\Connected {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("email", new \Quantyl\Form\Fields\Email(\I18n::EMAIL())) ;
        $form->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND()))
           ->setCallBack($this, "onProceed");
            
    }
    
    public function onProceed($data) {
        $u = $this->getUser() ;
        $u->changeMail($data["email"]) ;
        $u->sendMailCheck() ;
        
        $this->setAnswer(new \Quantyl\Answer\Message(\I18n::EMAIL_CHANGED_SUCCESSFULL($u->email))) ;
    }
    
}
