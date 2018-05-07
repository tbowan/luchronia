<?php

namespace Quantyl\ACL ;

class Admin implements ACL {
    
    private $_cfg ;
    
    public function __construct($config) {
        $this->_cfg = $config ;
    }
    
    public function checkPermission() {
        // Need CHaracter
        $user = isset($_SESSION["user"]) ? $_SESSION["user"] : null ;
        
        $mode = $this->_cfg["admin.mode"] ;
        
        if ($mode != "database") {
            return ;
        }
        
        $group_table    = \Quantyl\Dao\BddTable::fromTable($this->_cfg["admin.group_table"]) ;
        $role_table     = \Quantyl\Dao\BddTable::fromTable($this->_cfg["admin.role_table"]) ;
        $group          = $group_table->GetByName($this->_cfg["admin.group_name"]) ;
        
        if ($user === null || ! $role_table->hasRole($user, $group)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_ADMIN()) ;
        }
    }
            
}
