<?php

namespace Widget\View ;

class UnconnectedMenu extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        $res = "" ;
        $res .= "<ul>" ;
        $res .= "<li><a href=\"/User/Login\">" . \I18n::LOGIN() . "</a></li>" ;
        $res .= "<li><a href=\"/User/Create\">" . \I18n::REGISTER() . "</a></li>" ;
        $res .= "</ul>" ;
        return $res ;
    }
}
