<?php

namespace Services\Base ;

class Connected extends \Quantyl\Service\EnhancedService {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! isset($_SESSION["auth"])) {
            throw new \Quantyl\Exception\Http\ClientUnauthorized() ;
        }
    }
    
    public function getUser() {
        return $_SESSION["user"] ;
    }

    public function getAuth() {
        return $_SESSION["user"] ;
    }

}
