<?php

namespace Services\Base ;

class Admin extends Connected {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $acl = new \Quantyl\ACL\Admin($req->getServer()->getConfig()) ;
        $acl->checkPermission() ;
    }
    
}
