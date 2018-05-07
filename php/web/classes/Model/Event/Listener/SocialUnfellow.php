<?php

namespace Model\Event\Listener ;

class SocialUnfellow extends Listener {
    
    public function Social_Unfellow(\Model\Game\Character $a, \Model\Game\Character $b) {
        $char = $b ;
        $user = $char->user ;
        if ($user->mailon_following && $user->email_valid) {
            $author  = $a ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_UNFELLOW_SUBJECT(),
                "" . \I18n::ONMAIL_UNFELLOW_MESSAGE(
                        $author->getName())
                ) ;
        }
    }
    
}
