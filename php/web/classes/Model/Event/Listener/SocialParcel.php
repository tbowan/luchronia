<?php

namespace Model\Event\Listener ;

class SocialParcel extends Listener {
    
    public function Social_Parcel(\Model\Game\Post\Parcel $parcel) {
        $char = $parcel->recipient ;
        $user = $char->user ;
        if ($user->mailon_parcel && $user->email_valid) {
            $author = $parcel->sender ;
            $date   = $parcel->tf ;
            $destination = $parcel->destination ;
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_PARCEL_SUBJECT(),
                "" . \I18n::ONMAIL_PARCEL_MESSAGE(
                        $author->getName(),
                        $destination->getName(),
                        \I18n::_date_time($date-DT))
                ) ;
        }
    }
    
}
