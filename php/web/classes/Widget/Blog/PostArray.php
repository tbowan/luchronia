<?php

namespace Widget\Blog ;

class PostArray extends \Quantyl\Answer\Widget {
    
    private $_posts ;
    
    public function __construct($posts) {
        $this->_posts = $posts ;
    }
    
    public function getContent() {
        
        $res = "" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::DATE(),
            \I18n::TITLE(),
            \I18n::CATEGORY(),
            \I18n::ACTIONS()
        )) ;
        
        foreach ($this->_posts as $p) {
            $table->addRow(array(
                \I18n::_date_time($p->date - DT),
                new \Quantyl\XML\Html\A("/Blog/Post?id={$p->id}", $p->getName()),
                $p->category->getName(),
                " <a href=\"/Blog/EditPost?id={$p->id}\">" . \I18n::EDIT() . "</a>"
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
