<?php

namespace Model\Event\Listener ;

class SocialCommerceItem extends Listener {
    
    public function Social_Commerce_Item(\Model\Game\Trading\Character\Market\Sell $sell) {
        $char = $sell->character ;
        $user = $char->user ;      
        if ($user->mailon_commerce_item && $user->email_valid) {
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_COMMERCE_ITEM_SUBJECT(),
                "" . \I18n::ONMAIL_COMMERCE_ITEM_MESSAGE(
                        $sell->ressource->getName(),
                        $sell->market->city->getName()
                        )
                ) ;
        }
    }
    
}
