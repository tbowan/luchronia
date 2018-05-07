<?php

namespace Model\Event\Listener ;

class SocialFriendshipRequest extends Listener {
    
    public function Social_Friendship_Request(\Model\Game\Social\Request $request) {
        $char = $request->b ;
        $user = $char->user ;
        if ($user->mailon_friendship && $user->email_valid) {
            $author  = $request->a ;
            $message = $request->msg;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_FRIENDSHIPREQUEST_SUBJECT(),
                "" . \I18n::ONMAIL_FRIENDSHIPREQUEST_MESSAGE(
                        $author->getName(),
                        $message)
                ) ;
        }
    }
    
}
