<?php

namespace Answer\Widget\User ;

class Notifications extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Identity\User $u, $classes = "") {
        if ($u->email_valid) {
            $msg = \I18n::USER_MAIL_ONVALID($u->email) ;
        } else {
            $msg = \I18n::USER_MAIL_ONINVALID($u->email) ;
        }
        parent::__construct(\I18n::USER_ONMAIL() , "", "", $msg, $classes);
    }
    
}
