<?php

namespace Answer\Widget\Game\Forum ;

class Category extends \Quantyl\Answer\Widget {
    
    private $_category ;
    
    public function __construct(\Model\Game\Forum\Category $category) {
        parent::__construct();
        $this->_category = $category ;
    }
    
    private function getIcon(\Model\Game\Forum\Thread $th) {
        $icons = "" ;
        if ($th->pinned) {
            $icons .= "<img src=\"/Media/icones/base/star.png\" class=\"icone-med\"/>" ;
        }

        if ($th->closed) {
            $icons .= "<img src=\"/Media/icones/base/lock.png\" class=\"icone-med\"/>" ;
        }
        return $icons ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::THREAD_LIST() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ICO(),
            \I18n::THREAD(),
            \I18n::MESSAGES(),
            \I18n::VIEWED(),
            \I18n::LAST_MESSAGE(),
        )) ;
        
        foreach (\Model\Game\Forum\Thread::getFromCategory($this->_category) as $thread) {
            $last = \Model\Game\Forum\Post::LastFromThread($thread) ;
            $table->addRow(array(
                $this->getIcon($thread),
                new \Quantyl\XML\Html\A("/Game/Forum/Thread?thread={$thread->id}", $thread->getName()) . "<br/>"
                . \I18n::BY() . " : " . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$thread->author->id}", $thread->author->getName()),
                \Model\Game\Forum\Post::CountFromThread($thread),
                $thread->viewed,
                \I18n::_date_time($last->pub_date - DT) . "<br/>"
                . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$last->pub_author->id}", $last->pub_author->getName()),
            )) ;
        }
        
        $res .= $table ;
        
        $res .= "<p>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Forum/AddThread?category={$this->_category->id}", \I18n::ADD_THREAD()) ;
        $res .= "</p>" ;
        
        return $res ;
        
    }
    
}
