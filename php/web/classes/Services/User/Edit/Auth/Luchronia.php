<?php

namespace Services\User\Edit\Auth ;

use I18n;
use Model\Identity\Authentication\Luchronia as MLuchronia;
use Quantyl\Exception\Http\ClientBadRequest;
use Quantyl\Exception\Http\ClientForbidden;
use Quantyl\Form\Fields\Password;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Services\Base\Connected;

class Luchronia extends Connected {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("auth", new Id(MLuchronia::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $auth = $this->auth ;
        $user = $this->getUser() ;
        if (! $user->equals($auth->user)) {
            throw new ClientForbidden() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("nickname", new Text    (I18n::NICKNAME(), true))
             ->setValue($this->auth->nickname);
        $form->addInput("password", new Password(I18n::PASSWORD(), true)) ;
        $form->addInput("password_bis", new Password(I18n::PASSWORD_BIS(), true)) ;
        return $form ;
    }
    
    public function onProceed($params) {
        
        // Check passwords
        if ($params["password"] != $params["password_bis"]) {
            throw new ClientBadRequest(I18n::PASSWORD_DONT_MATCH()) ;
        }
        
        // check user already exists
        $already = MLuchronia::GetByName($params["nickname"]) ;
        if ($already !== null && ! $already->equals($this->auth)) {
            throw new ClientBadRequest(I18n::NICKNAME_ALREADY_TAKEN()) ;
        }
        
        // setup authentication
        $this->auth->changeAuth($params["nickname"], $params["password"]) ;
        
        $_SESSION["auth"] = $this->auth ;

        $this->setAnswer(new \Answer\Widget\User\ChangeAuthSuccess($this->auth)) ;
        
    }
}
