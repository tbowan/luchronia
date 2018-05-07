<?php

namespace Answer\Widget\Game\Forum ;

class Root extends \Quantyl\Answer\Widget {
    
    private $_instance ;
    private $_viewer ;
    
    public function __construct(\Model\Game\Building\Instance $forum, \Model\Game\Character $viewer) {
        $this->_instance = $forum ;
        $this->_viewer   = $viewer ;
    }
    
    public function getModerators(\Model\Game\Forum\Category $category) {
        $modos = array() ;
        foreach (\Model\Game\Forum\Moderator::getFromCategory($category) as $modo) {
            $modos[] = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$modo->moderator->id}", $modo->moderator->getName()) ;
        }
        return implode(", ", $modos) ;
    }
    
    private function addCategory(\Model\Game\Forum\Category $category, &$table) {
        $last = \Model\Game\Forum\Post::LastFromCategory($category) ;
        if ($last == null) {
            $l = "-" ;
        } else {
            $l = new \Quantyl\XML\Html\A("/Game/Forum/Thread?thread={$last->thread->id}", $last->thread->getName()) . "<br/>"
                    . \I18n::_date_time($last->pub_date - DT) . "<br/>"
                    . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$last->pub_author->id}", $last->pub_author->getName()) ;
        }
        $table->addRow(array(
            new \Quantyl\XML\Html\A("/Game/Forum/Category?category={$category->id}", $category->getName())
                    . "<br/>" . $category->description
                    . "<br/><strong>" . \I18n::MODERATORS() . " : </strong> " . $this->getModerators($category),
            \Model\Game\Forum\Thread::CountFromCategory($category),
            \Model\Game\Forum\Post::CountFromCategory($category),
            $l
        )) ;
    }
    
    public function getContent() {
        
        $table = new \Quantyl\XML\Html\Table() ;
        
        $table->addHeaders(array(
            \I18n::CATEGORY(),
            \I18n::THREADS(),
            \I18n::POSTS(),
            \I18n::LAST_POST(),
        )) ;
        
        foreach (\Model\Game\Forum\Category::GetFromInstance($this->_instance) as $category) {
            if ($category->canRW($this->_viewer)) {
                $this->addCategory($category, $table) ;
            }
        }
        return "" . $table ;
    }
    
    
    
}
