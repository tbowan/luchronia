<?php

namespace Services\User\Authentication ;

class Forgotten extends \Quantyl\Service\EnhancedService {
    
    private $_until ;
    
    public function init() {
        parent::init();
        
        $this->_until = \Model\Quantyl\Config::ValueFromKey("AUTH_REINIT_UNTIL") ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::FORGOTTEN_AUTH()) ;
        $form->addInput("login", new \Quantyl\Form\Fields\Text(\I18n::NICKNAME())) ;
        $form->addInput("mail", new \Quantyl\Form\Fields\Email(\I18n::EMAIL())) ;
    }
    
    public function onLogin($login) {
        $auth = \Model\Identity\Authentication\Luchronia::GetByName($login) ;
        if ($auth != null) {
            $user = $auth->user ;
            $mail = $user->email ;
            return $this->onEmail($mail, $user) ;
        } else {
            return false ;
        }
    }
    
    public function onEmail($email, $user) {
        if ($user == null) {
            $users = \Model\Identity\User::GetByEmail($email) ;
            $res = true ;
            foreach ($users as $u) {
                $res = $res && $this->onEmail($email, $u) ;
            }
            return $res ;
        } else if ($user->email_valid) {
            
            $token = \Model\Identity\Authentication\Reinit::createFromValues(array(
                "user" => $user,
                "until" => time() + $this->_until
            )) ;
            
            $auth = \Model\Identity\Authentication\Luchronia::GetFromUser($user) ;
            
            $url = "http://luchronia.com/User/Authentication/Reinit?token=". $token->token ;
            
            \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $email,
                \I18n::AUTH_REINIT_SUBJECT(),
                \I18n::AUTH_REINIT_MESSAGE(
                        $user->getName(),
                        $auth->nickname,
                        new \Quantyl\XML\Html\A($url, $url),
                        \I18n::_date_time($token->until))
                ) ;
            return $res ;
        } else {
            return false ;
        }
    }
    
    public function onProceed($data) {
        
        
        if ($data["login"] != null) {
            $res = $this->onLogin($data["login"]) ;
        } else if ($data["mail"] != null) {
            $res = $this->onEmail($data["mail"]) ;
        }
        
        if (! $res) {
            throw new \Exception() ;
        }
        
        $this->setAnswer(
                new \Quantyl\Answer\Message(
                        \I18n::AUTH_REINIT_DONE(
                                \I18n::_date_time(time() + $this->_until)))) ;
        
    }
    
}
