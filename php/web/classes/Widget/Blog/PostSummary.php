<?php

namespace Widget\Blog ;

class PostSummary extends \Quantyl\Answer\Widget {
    
    private $_post ;
    
    public function __construct(\Model\Blog\Post $p) {
        $this->_post = $p ;
    }

    public function getContent() {
        $p = $this->_post ;
        
        $res = '<div class="post-summary">' ;
        $res .= "<h2>". $p->title . "</h2>" ;
        $res .= '<p class="meta-when">' . \I18n::FROM_WHEN($p->author->getName(), \I18n::_date_time($p->date - DT)) . '</p>' ;
        $res .= '<div class="post-icon">' . $p->getImage("blog-illustr-summary") . '</div>' ;
        $res .= '<div class="post-head">'
                . $p->head
                . '<p><span class="knowmore"><a href="/Blog/Post?id=' . $p->id . '">' . \I18n::KNOW_MORE() . '</a></span></p>'
                . '</div>' ;
        $res .= '</div>' ;
        
        return $res ;
    }

}
