<?php

namespace Answer\Widget\Game\Social ;

class GlobSub extends \Answer\Widget\Misc\Section{
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        parent::__construct(
                \I18n::GLOBAL_SUBSCRIPTIONS(),
                "", "",
                $this->_getLastMessages($me),
                $classes);
    }
    
    private function _getLastMessages(\Model\Game\Character $me) {
        $res = "" ;
        
        foreach (\Model\Forum\Thread::GetFollowedBy($me) as $thread) {
            $post = \Model\Forum\Post::GetLastFromThread($thread) ;
            $postlink   = new \Quantyl\XML\Html\A("/Forum/Thread?id={$thread->id}", $thread->title) ;
            $authorlink = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$post->author->id}", $post->author->getName()) ;
            
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
        
    }
    
}
