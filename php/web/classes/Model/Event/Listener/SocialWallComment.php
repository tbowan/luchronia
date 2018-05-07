<?php

namespace Model\Event\Listener ;

class SocialWallComment extends Listener {
    
    public function Social_Wall_Comment(\Model\Game\Social\Comment $comment) {
        $post = $comment->post;
        $char = $post->author;
        $user = $char->user ;
                
        if ($user->mailon_wall && $user->email_valid) {
            $author  = $comment->author ;
            $date    = $comment->date;
            $content = $comment->content;
            $initial = $post->content;
            
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_WALL_COMMENT_SUBJECT(),
                "" . \I18n::ONMAIL_WALL_COMMENT_MESSAGE(
                        $author->getName(),
                        \I18n::_date_time($date-DT),
                        $content,
                        $initial
                        )
                ) ;
        }
    }
    
}
