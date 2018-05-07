<?php

namespace Answer\Widget\Game\Social ;

class CitySub extends \Answer\Widget\Misc\Section{
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        parent::__construct(
                \I18n::CITY_SUBSCRIPTIONS(),
                "", "",
                $this->_getLastMessages($me),
                $classes);
    }
    
    private function _getLastMessages(\Model\Game\Character $me) {
        $res = "" ;
        
        foreach (\Model\Game\Forum\Thread::GetFollowedBy($me) as $thread) {
            $post = \Model\Game\Forum\Post::LastFromThread($thread) ;
            $postlink   = new \Quantyl\XML\Html\A("/Game/Forum/Thread?thread={$thread->id}", $thread->title) ;
            $authorlink = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$post->pub_author->id}", $post->pub_author->getName()) ;
            
            $res .= "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $thread->getImages . "</div>" ;
            $res .= "<div class=\"description\">"
                    . "<div class=\"name\">$postlink</div>"
                    . "<div class=\"last\">" . \I18n::_date_time($post->date - DT) . " $authorlink</div>"
                    . "</div>" ;
            $res .= "</div></li>" ;
        }
        
        if ($res != "") {
            return "<ul class=\"itemList\">$res</ul>" ;
        } else {
            return \I18n::NO_SUBSCRIBTION() ;
        }
        
        return $res ;
        
    }
    
}
