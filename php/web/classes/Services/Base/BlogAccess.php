<?php

namespace Services\Base ;

abstract class BlogAccess extends Character {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req) ;
        
        $cat = $this->getCategory() ;
        if ($cat === null || ! $this->getCategory()->canAccess($this->getUser())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public abstract function getCategory() ;
}
