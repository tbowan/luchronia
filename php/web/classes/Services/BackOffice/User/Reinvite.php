<?php

namespace Services\BackOffice\User ;

class Reinvite extends \Services\Base\Admin {

    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sponsor", new \Quantyl\Form\Model\Id(\Model\Identity\Sponsor::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->sponsor->protege != null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REQUEST_ALREADY_ACCEPTED()) ;
        } else if ($this->sponsor->mail == null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REQUEST_NEED_MAIL()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::REINVITE_REQUEST_MSG($this->sponsor->mail)) ;
        
    }
    
    public function onProceed($data) {

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
    
}
