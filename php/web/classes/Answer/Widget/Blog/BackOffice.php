<?php

namespace Answer\Widget\Blog ;

class BackOffice extends \Answer\Widget\Misc\Section {
    
    public function __construct() {
    
        $res  = "<ul>" ;
        $res .= "<li><a href=\"/Blog/AddPost\">" . \I18n::ADD_POST() . "</a></li>" ;
        $res .= "<li><a href=\"/Blog/MyPosts\">"  . \I18n::MY_POST()  . "</a></li>" ;
        $res .= "</ul>";
        
        parent::__construct(\I18n::BACKOFFICE(), "", "", $res, "card-1-3 right") ;
    }
}
