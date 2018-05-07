<?php

namespace Model\Event\Listener ;

class SocialFriendshipRefuse extends Listener {
    
    public function Social_Friendship_Refuse(\Model\Game\Character $a, \Model\Game\Character $b) {
        $char = $a ;
        $user = $char->user ;
        if ($user->mailon_friendship && $user->email_valid) {
            $author  = $b ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_FRIENDSHIPREFUSE_SUBJECT(),
                "" . \I18n::ONMAIL_FRIENDSHIPREFUSE_MESSAGE(
                        $author->getName())
                ) ;
        }
    }
    
}
