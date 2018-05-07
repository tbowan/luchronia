<?php

namespace Answer\Widget\User ;

class Authentication extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Identity\User $u, $classes = "") {
        
        $res = "<ul>" ;
        $auth = \Model\Identity\Authentication\Luchronia::GetFromUser($u) ;
        $res .= "<li>" . $auth->nickname . "</li>" ;
        $res .= "</ul>" ;
       
        parent::__construct(\I18n::USER_AUTHENTICATION(), new \Quantyl\XML\Html\A("/User/Edit/Auth/Luchronia?auth={$auth->id}", \I18n::CHANGE_AUTH()), "", $res, $classes);
    }

    
}
