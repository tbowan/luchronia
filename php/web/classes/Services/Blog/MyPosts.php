<?php

namespace Services\Blog ;

use Model\Blog\Category;
use Model\Blog\Post;
use Quantyl\Request\Request;
use Widget\Blog\PostArray;

class MyPosts extends \Services\Base\Character {
    
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $user = $this->getUser() ;
        $cats = Category::getAll();
        
        $hasaccess = false ;
        foreach ($cats as $c) {
            $hasaccess = $hasaccess || $c->canAccess($user) ;
        }
        
        if (! $hasaccess) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::HAS_NOT_ACCESS_TO_CATEGORY()) ;
        }
    }
    
    public function getView() {
        $c = $this->getCharacter() ;
        return new PostArray(Post::FromAuthor($c)) ;
    }
    
}
