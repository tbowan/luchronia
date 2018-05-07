<?php

namespace Services\User\Mail ;

class Validate extends \Services\Base\Connected {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("token", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        
        $u = $this->getUser() ;
        
        if ($u->email_token !== $this->token) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
        
    }
    
    public function getView() {
        
        $u = $this->getUser() ;
        
        $u->email_valid = true ;
        $u->update() ;
        
        return new \Quantyl\Answer\Message(\I18n::EMAIL_VALIDATION_SUCCESS()) ;
    }
    
    
}
