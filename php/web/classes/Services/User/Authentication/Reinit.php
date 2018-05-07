<?php


namespace Services\User\Authentication ;

class Reinit extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("token", new \Quantyl\Form\Model\Name(\Model\Identity\Authentication\Reinit::getBddTable())) ;
    }
    
    private $_user ;
    private $_auth ;
    
    public function init() {
        parent::init();
        
        $this->_user = $this->token->user ;
        $this->_auth = \Model\Identity\Authentication\Luchronia::GetFromUser($this->_user) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $nickname = $this->_auth->nickname ;
        
        $form->addMessage(\I18n::AUTH_REINIT_FORM_MESSAGE($nickname)) ;
        $form->addInput("password",     new \Quantyl\Form\Fields\Password(\I18n::PASSWORD(), true)) ;
        $form->addInput("password_bis", new \Quantyl\Form\Fields\Password(\I18n::PASSWORD_BIS(), true)) ;
    }
    
    public function onProceed($data) {
        // Check passwords
        if ($data["password"] != $data["password_bis"]) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::PASSWORD_DONT_MATCH()) ;
        }
        
        // setup authentication
        $this->_auth->changeAuth($this->_auth->nickname, $data["password"]) ;
        
        $this->token->delete() ;
        
        $this->setAnswer(\Services\User\Login::doLogin($this->_auth)) ;
        
    }
    
}
