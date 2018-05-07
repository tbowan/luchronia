<?php

namespace Widget\Blog ;

class PostList extends \Quantyl\Answer\Widget {
    
    private $_posts ;
    
    public function __construct($posts) {
        $this->_posts = $posts ;
    }
    
    public function getContent() {

        $res = "" ;
        foreach ($this->_posts as $p) {
            $res .= new PostSummary($p) ;
        }
        
        return $res ;
        
    }
    
}
