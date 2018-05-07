<?php

namespace Answer\Widget\Blog ;

class Categories extends \Answer\Widget\Misc\Section {
    
    public function __construct($user, $lang) {
        $res  = "<ul>" ;
        $res .= "<li>"
                . "<a href=\"/Blog/RSS\">" . \I18n::RSS_ICO() . "</a> "
                . "<a href=\"/Blog\">" . \I18n::ALL_CATS() . "</a>"
                . "</li>" ;
        
        //Afficher les différents tris possibles
        $has_access = false ;
        $cats = \Model\Blog\Category::GetFromLang($lang);
        foreach ($cats as $c) {
            $res .= "<li>"
                    . "<a href=\"/Blog/RSS?category=" . $c->id . "\">" . \I18n::RSS_ICO() ."</a> "
                    . "<a href=\"/Blog/Category?id=" . $c->id . "\">".$c->name ."</a>"
                    . "</li>";
            $has_access = $has_access || $c->canAccess($user) ;
        }
        $res .= "</ul>";
        
        parent::__construct(\I18n::CATEGORIES(), "", "", $res, "card-1-3 right") ;
    }
    
}
