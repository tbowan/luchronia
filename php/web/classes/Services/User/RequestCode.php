<?php

namespace Services\User ;

class RequestCode extends \Quantyl\Service\EnhancedService {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("mail", new \Quantyl\Form\Fields\Email(\I18n::EMAIL())) ;
        $form->addInput("mailbis", new \Quantyl\Form\Fields\Email(\I18n::EMAIL_BIS())) ;
        $form->addInput("message", new \Quantyl\Form\Fields\TextArea(\I18n::MESSAGE())) ;
        $form->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND()))
             ->setCallBack($this,"onProceed");
    }
    
    public function onProceed($data) {
        
        if ($data["mail"] != $data["mailbis"]) {
            throw new \Exception(\I18n::EXP_EMAIL_DONT_EQUALS()) ;
        }
        
        $already = \Model\Identity\Sponsor::GetFromMail($data["mail"]) ;
        if ($already != null) {
            throw new \Exception(\I18n::REQUESTCODE_ALREADY_MAIL($data["mail"])) ;
        }
        
        $inv = \Model\Identity\Sponsor::createFromValues(array(
            "sponsor" => null,
            "date" => time(),
            "mail" => $data["mail"],
            "message" => "<pre>" . $data["message"] . "</pre>",
            "protege" => null,
            "code" => null
        )) ;
        
        $this->setAnswer(new \Quantyl\Answer\Message(\I18n::REQUESTCODE_ALREADY_DONE())) ;
        
        
        \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                \I18n::INVITATION_REQUEST_SUBJECT($data["mail"]),
                \I18n::INVITATION_REQUEST_MESSAGE(
                        $data["mail"],
                        $data["message"],
                        $inv->id)
                ) ;
    }
    
}
