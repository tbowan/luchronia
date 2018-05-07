<?php

namespace Model\Event\Listener ;

class SocialFriendshipAccept extends Listener {
    
    public function Social_Friendship_Accept(\Model\Game\Social\Friend $friend) {
        $char = $friend->a ;
        $user = $char->user ;
        if ($user->mailon_friendship && $user->email_valid) {
            $author  = $friend->b ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_FRIENDSHIPACCEPT_SUBJECT(),
                "" . \I18n::ONMAIL_FRIENDSHIPACCEPT_MESSAGE(
                        $author->getName())
                ) ;
        }
    }
    
}
