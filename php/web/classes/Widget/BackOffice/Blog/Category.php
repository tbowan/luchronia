<?php

namespace Widget\BackOffice\Blog ;

class Category extends \Quantyl\Answer\Widget {
    
    private $_category ;
    
    public function __construct(\Model\Blog\Category $c) {
        $this->_category = $c ;
    }

    public function getContent() {
        $c = $this->_category ;
        
        $res = "<h2>"
                . $c->name
                . "</h2>" ;

        $res .= "<p><strong>" . \I18n::BACKOFFICE_BLOG_EDIT_CATEGORY() . "</strong> : ";
        $res .= " <a href=\"/BackOffice/Blog/EditCategory?category={$c->id}\">" . \I18n::EDIT() . "</a>";
        $res .= " <a href=\"/BackOffice/Blog/DeleteCategory?category={$c->id}\">" . \I18n::DELETE() . "</a>";
        $res .= "</p>";
        
        // Table des posts de la catégorie
        $posts = $c->getPosts(true) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::DATE(),
            \I18n::TITLE(),
            \I18n::AUTHOR(),
            \I18n::PAST(),
            \I18n::PUBLISHED(),
            \I18n::ACTIONS()
        )) ;
        
        foreach ($posts as $p) {
            $table->addRow(array(
                    \I18n::_date_time($p->date - DT),
                    "<a href=\"/Blog/Post?id={$p->id}\">" . $p->title . "</a>" ,
                    $p->author->getName(),
                    ($p->date < time() ? \I18n::YES_ICO(): \I18n::NO_ICO()),
                    ($p->published ? \I18n::YES_ICO() : \I18n::NO_ICO()),
                    " <a href=\"/BackOffice/Blog/EditPost?post={$p->id}\">" . \I18n::EDIT() . "</a>" .
                    " <a href=\"/BackOffice/Blog/DeletePost?post={$p->id}\">" . \I18n::DELETE() . "</a>"
                )) ;
        }
        $res .= $table ;
        
        $res .= new \Quantyl\XML\Html\A(
                "/BackOffice/Blog/AddPost?category={$c->id}",
                \I18n::ADD_BLOGPOST()
                        ) ;

        return $res  ;
    }

}