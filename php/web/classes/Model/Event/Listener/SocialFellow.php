<?php

namespace Model\Event\Listener ;

class SocialFellow extends Listener {
    
    public function Social_Fellow(\Model\Game\Social\Follower $fellower) {
        $char = $fellower->b ;
        $user = $char->user ;
        if ($user->mailon_following && $user->email_valid) {
            $author  = $fellower->a ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_FELLOW_SUBJECT(),
                "" . \I18n::ONMAIL_FELLOW_MESSAGE(
                        $author->getName())
                ) ;
        }
    }
    
}
