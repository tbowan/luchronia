<?php

namespace Services\User ;

class AddSponsor extends \Services\Base\Connected {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $min_level      = \Model\Quantyl\Config::ValueFromKey("INVITATION_MIN_LEVEL", 0) ;
        $max_invitation = \Model\Quantyl\Config::ValueFromKey("INVITATION_MAX_INVITATION", 1) ;
        $quota_delay    = \Model\Quantyl\Config::ValueFromKey("INVITATION_QUOTA_DELAY", 0) ;
        
        
        if ($this->getUser()->character->level < $min_level) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_INVITATION_LEVEL($min_level)) ;
        } else if (\Model\Identity\Sponsor::CountFromUserSince($this->getUser(), time() - 24*60*60*$quota_delay) > $max_invitation) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_INVITATION_QUOTA($max_invitation, $quota_delay)) ;
        }

    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::ADD_SPONSOR_MESSAGE()) ;
        $form->addInput("mail", new \Quantyl\Form\Fields\Email(\I18n::EMAIL())) ;
        $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
    }
    
    public function onProceed($data) {
        
        // Check havent already invited this mail
        
        if (\Model\Identity\Sponsor::hasAlreadyInvidedd($this->getUser(), $data["mail"])) {
            throw new \Exception(\I18n::EXP_INVITATION_ALREADY($data["mail"])) ;
        }
        
        // Create sponsoring
        $sponsor = \Model\Identity\Sponsor::createFromValues(array(
            "sponsor" => $this->getUser(),
            "date" => time(),
            "mail" => $data["mail"],
            "message" => $data["message"]
        )) ;
        
        $u = $this->getUser() ;
        $c = $u->character ;
        
        \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $data["mail"], 
                \I18n::SPONSOR_SUBJET_DEFAULT(),
                \I18n::SPONSOR_MAIL_CONTENT(
                        $c->getName(),
                        $u->email,
                        $data["message"],
                        $data["mail"],
                        $data["mail"])
                ) ;
    }
    
}
