<?php

namespace Model\Event\Listener ;

class SocialFriendshipSuppress extends Listener {
    
    public function Social_Friendship_Suppress(\Model\Game\Character $a, \Model\Game\Character $b) {
        $char = $b ;
        $user = $char->user ;
        if ($user->mailon_friendship && $user->email_valid) {
            $author  = $a ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_FRIENDSHIPSUPPRESS_SUBJECT(),
                "" . \I18n::ONMAIL_FRIENDSHIPSUPPRESS_MESSAGE(
                        $author->getName())
                ) ;
        }
    }
    
}
