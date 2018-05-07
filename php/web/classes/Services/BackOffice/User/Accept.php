<?php

namespace Services\BackOffice\User ;


class Accept extends \Services\Base\Admin {

    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sponsor", new \Quantyl\Form\Model\Id(\Model\Identity\Sponsor::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->sponsor->sponsor != null || $this->sponsor->protege != null) {
            throw new \Exception(\I18n::EXP_REQUEST_ALREADY_ACCEPTED()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::ACCEPT_REQUEST_CODE(
                $this->sponsor->mail,
                \I18n::_date_time($this->sponsor->date),
                $this->sponsor->message)) ;
        
        $form->addSubmit("cancel", new \Quantyl\Form\Fields\Submit(\I18n::SUBMIT_CANCEL(), false))
             ->setCallBack($this, "onCancel") ;
        
        $form->addSubmit("refuse", new \Quantyl\Form\Fields\Submit(\I18n::SUBMIT_REFUSE(), false))
             ->setCallBack($this, "onRefuse") ;
        
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SUBMIT_PROCEED()))
             ->setCallBack($this, "onProceed") ;
        
    }
    
    public function onProceed($data) {
        
        // Set me as sponsor
        $this->sponsor->sponsor = $this->getUser() ;
        $this->sponsor->update() ;
        
        // Send mail to confirm code
        \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $this->sponsor->mail,
                \I18n::INVITATION_ACCEPTED_SUBJECT(),
                \I18n::INVITATION_ACCEPTED_MESSAGE(
                        $this->sponsor->code,
                        $this->sponsor->code,
                        $this->sponsor->code,
                        $this->sponsor->mail)
                ) ;
        
    }
    
    public function onRefuse($data) {
        $this->sponsor->delete() ;
    }
    
}
