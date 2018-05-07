<?php

namespace Services\BackOffice\Blog ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        
        $answer = new \Quantyl\Answer\ListAnswer() ;

        $answer->addAnswer(new \Quantyl\XML\Html\A(
                "/BackOffice/Blog/AddCategory",
                 \I18n::ADD_BLOGCATEGORY()
                )) ;
        
        $categories = \Model\Blog\Category::getAll() ;
        foreach ($categories as $cat) {
            $answer->addAnswer(new \Widget\BackOffice\Blog\Category($cat)) ;
        }
        
        return $answer;
    }
    
}
