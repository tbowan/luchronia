<?php

namespace Services\Blog ;

use Model\Blog\Category;
use Model\Blog\Post;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Service\EnhancedService;
use Widget\Blog\Rss as WRSS;

class RSS extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("category", new Id(Category::getBddTable(), "", false)) ;
    }
    
    public function getView() {
            
        $req = $this->getRequest() ;
        
        $lang = \Model\I18n\Lang::GetCurrent() ;
        
        if ($this->category == null) {
            $posts = Post::GetLasts($lang, 10) ;
        } else {
            $posts = Post::LastFromCategory(10, $this->category) ;
        }
        
        return new WRSS(
                \I18n::LUCHRONIA(),
                $req->getHostName(),
                $posts
                ) ;
        
    }
}

?>
