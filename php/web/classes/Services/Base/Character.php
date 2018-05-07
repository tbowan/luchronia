<?php

namespace Services\Base ;

class Character extends Connected {
    
    public function onEmailInvalidException(\Model\Identity\User $u) {
        // 1. change mail
        $f1 = new \Quantyl\Form\Form() ;
        $f1->setAction("/User/Mail/Set", "post") ;
        $f1->addInput("email", new \Quantyl\Form\Fields\Email(\I18n::EMAIL()))
           ->setValue($u->email);
        $f1->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
        $msg1 = new \Answer\Widget\Misc\Card(\I18n::CHANGE_EMAIL_TITLE(), \I18n::CHANGE_EMAIL_MESSAGE($f1->getContent())) ;

        // 2. have a code
        $f2 = new \Quantyl\Form\Form() ;
        $f2->setAction("/User/Mail/Validate", "get") ;
        $f2->addInput("token", new \Quantyl\Form\Fields\Text(\I18n::TOKEN())) ;
        $f2->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
        $msg2 = new \Answer\Widget\Misc\Card(\I18n::VALIDATE_EMAIL_TITLE(), \I18n::VALIDATE_EMAIL_MESSAGE($f2->getContent())) ;
        
        return "" . \I18n::EMAIL_INVALID_MESSAGE() . $msg2 . $msg1 ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! isset($_SESSION["char"])) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        // Valid Mail
        $u = $this->getUser() ;
        if (! $u->email_valid) {
            throw new \Quantyl\Exception\Http\ClientForbidden($this->onEmailInvalidException($u)) ;
        }
        
        $char = $this->getCharacter() ;
        if ($char === null) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else {
            $char->touch() ;
        }
        
        // CGVU
        $last = \Model\Identity\Cgvu::getLast() ;
        if ($last != null) {
            $accepted = \Model\Identity\Accepted::getAccepted($this->getUser(), $last) ;
            if ($accepted == null) {
                throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CGVU_CHANGE_MUST_ACCEPT()) ;
            }
        }
        
    }
    
    public function getCharacter() {
        return $_SESSION["char"] ;
    }
    
}
