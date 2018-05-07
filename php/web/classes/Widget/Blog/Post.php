<?php

namespace Widget\Blog ;

use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Div;
use Quantyl\XML\Html\Span;
use Quantyl\XML\Raw;
use Widget\Game\Character\LinkedName ;


class Post extends \Quantyl\Answer\Widget {
    
    private $_post ;
    private $_admin ;
    
    public function __construct(\Model\Blog\Post $p, $admin) {
        $this->_post = $p ;
        $this->_admin = $admin ;
    }

    public function getContent() {
        
        $div = new Div("post") ;
        
        $meta = $div->addChild(new Div("meta")) ;
        
        $avatar = $meta->addChild(new Span("avatar")) ;
        $avatar->addChild($this->_post->author->getImage("mini")) ;
        
        $author = $meta->addChild(new Span("author")) ;
        $author->addChild(new Raw("" . new LinkedName($this->_post->author))) ;
        
        $date = $meta->addChild(new Span("date")) ;
        $date->addChild(new Raw(\I18n::_date_time($this->_post->date - DT))) ;
        
        $admin = $meta->addChild(new Span("admin")) ;
        if ($this->_admin) {
                $admin->addChild(new A("/Blog/EditPost?id={$this->_post->id}", \I18n::EDIT())) ;
        }
        
        $content = $div->addChild(new Div("content")) ;
        $content->addChild($this->_post->getImage("blog-illustr")) ;
        $content->addChild(new Raw($this->_post->head)) ;
        $content->addChild(new Raw($this->_post->content)) ;
                
        return "$div" ;

    }

}