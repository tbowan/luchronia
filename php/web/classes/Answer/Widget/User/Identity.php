<?php

namespace Answer\Widget\User ;

class Identity extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Identity\User $u, $classes = "") {
        
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::FIRST_NAME() . " :</strong> " . $u->first_name . "</li>" ;
        $res .= "<li><strong>" . \I18n::LAST_NAME() . " :</strong> " . $u->last_name . "</li>" ;
        $res .= "<li><strong>" . \I18n::BIRTHDAY() . " :</strong> " . \I18n::_date($u->birth) . "</li>" ;
        $res .= "<li><strong>" . \I18n::SEX() . " :</strong> " . $u->sex . "</li>" ;
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::USER_IDENTITY(), new \Quantyl\XML\Html\A("/User/Edit/Identity", \I18n::CHANGE_IDENTITY()), "", $res, $classes) ;
    }

    
}
