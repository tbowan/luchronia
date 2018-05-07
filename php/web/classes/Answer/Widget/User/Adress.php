<?php

namespace Answer\Widget\User ;

class Adress extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Identity\User $u, $classes = "") {
        
        $msg = "<p>" ;
        $msg .= $u->address . "<br/>" ;
        if ($u->address_compl != "") {
            $msg .= $u->address_compl . "<br/>" ;
        }
        $msg .= $u->code . " " ;
        $msg .= $u->city . "<br/>" ;
        $msg .= $u->state ;
        $msg .= "</p>" ;
        
        parent::__construct(\I18n::USER_ADDRESS() , new \Quantyl\XML\Html\A("/User/Edit/Address", \I18n::CHANGE_ADDRESS()), "", $msg, $classes);
    }
    
}
