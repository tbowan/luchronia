<?php

namespace Services\BackOffice\User ;
class Invite extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::BACK_OFFICE_INVITE_MESSAGE()) ;
        $form->addInput("mail", new \Quantyl\Form\Fields\Email(\I18n::EMAIL())) ;
        $form->addInput("code", new \Quantyl\Form\Fields\Text(\I18n::CODE())) ;
        $form->addInput("message", new \Quantyl\Form\Fields\FullHtml(\I18n::MESSAGE())) ;
    }
    
    public function onProceed($data) {
        $u = $this->getUser() ;
        $c = $u->character ;
        
        if ($data["mail"] != "" && \Model\Identity\Sponsor::hasAlreadyInvidedd($u, $data["mail"])) {
            throw new \Exception(\I18n::EXP_INVITATION_ALREADY($data["mail"])) ;
        }
        
        $inv = \Model\Identity\Sponsor::createFromValues(array(
            "sponsor" => $u,
            "date" => time(),
            "mail" => $data["mail"],
            "message" => $data["mail"] == "" ? "" : $data["message"],
            "protege" => null,
            "code" => $data["code"] == "" ? null : $data["code"]
        )) ;
        
        
        
        if ($data["mail"] != null) {
            $msg = \I18n::SPONSOR_MAIL_CONTENT(
                        $c->getName(),
                        \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                        $data["message"],
                        $inv->code,
                        $data["mail"]) ;
            
            \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $data["mail"], 
                \I18n::SPONSOR_SUBJET_DEFAULT(),
                $msg
                ) ;
        }
        
        $this->setAnswer(new \Quantyl\Answer\Message(\I18n::INVITATION_DONE($inv->mail, $inv->code, $inv->message, $inv->code))) ;
        
    }
    
}
