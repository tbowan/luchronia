<?php

namespace Services\Base ;

abstract class ForumView extends Character {
    

    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        $category    = $this->getCategory() ;
        $user        = $this->getUser() ;
            
        if ($category === null || (! $category->canView($user) && ! $category->canModerate($user))) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public abstract function getCategory() ;
    
}
