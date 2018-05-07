<?php

namespace Model\Event\Listener ;

class SocialForumFollow extends Listener {
    
    public function Social_Forum_Follow(\Model\Game\Character $character, \Model\Forum\Post $post) {
        $char = $character ;
        $user = $char->user ;
        if ($user->mailon_forum_follow && $user->email_valid) {
            $author  = $post->author ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_FORUM_FELLOW_SUBJECT(),
                "" . \I18n::ONMAIL_FORUM_FELLOW_MESSAGE(
                        $author->getName(), 
                        $post->content,
                        $post->thread->id
                        )
                ) ;
        }
    }
    
}
