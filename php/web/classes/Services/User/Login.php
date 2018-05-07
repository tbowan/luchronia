<?php

namespace Services\User ;

use Exception;
use Model\Identity\Authentication\Luchronia;
use Quantyl\Form\Fields\Password;
use Quantyl\Form\Fields\Submit;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Service\EnhancedService;

class Login extends EnhancedService {
    
    public function fillDataForm(Form &$form) {
        // Data
        $form->addInput("nickname", new Text    (\I18n::NICKNAME(), true)) ;
        $form->addInput("password", new Password(\I18n::PASSWORD(), true)) ;
        $form->addInput("remember", new \Quantyl\Form\Fields\Boolean(\I18n::REMEMBER())) ;
        // Submit
        
        $form->addMessage(new \Quantyl\XML\Html\A("/User/Authentication/Forgotten", \I18n::FORGOTTEN_PASSWORD())) ;
        
        $form->addSubmit("login", new Submit(\I18n::LOGIN()))
             ->setCallBack($this, "onProceed") ;
        return $form ;
    }
    
    public function onProceed($params) {
        
        $auth = Luchronia::GetByName($params["nickname"]) ;
        
        if ($auth === null) {
            throw new Exception(\I18n::EXP_NO_SUCH_NICKNAME());
        } else if (! $auth->checkPassword($params["password"])) {
            throw new Exception(\I18n::EXP_INVALID_PASSWORD());
        } else {
            $this->setAnswer(self::doLogin($auth, $params["remember"])) ;
        }
    }
    
    public function onForgot() {
        $this->setAnswer(new \Quantyl\Answer\Redirect("/User/Authentication/Forgotten")) ;
    }
    
    public static function doLogin(Luchronia $auth, $long) {
        session_regenerate_id(true) ;

        if ($auth->user->character == null) {
            $auth->user->character = $auth->user->getFirstCharacter() ;
            $auth->user->update() ;
        }

        $char = $auth->user->character ;
        $char->previous = $char->last ;
        $char->last = time() ;
        $char->update() ;

        $_SESSION["auth"] = $auth ;
        $_SESSION["user"] = $auth->user ;
        $_SESSION["char"] = $char ;

        if ($long) {
            
            \Quantyl\Session\Session::setLong() ;
        }
        
        return new \Quantyl\Answer\Redirect("/Game/") ;
    }

    
}
