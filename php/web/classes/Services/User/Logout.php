<?php

namespace Services\User ;


class Logout extends \Services\Base\Connected {
    
    
    public function getView() {
        session_destroy() ;
        $_SESSION = array() ;
        
        return new \Answer\View\User\Logout($this) ;
    }
    
    
}
