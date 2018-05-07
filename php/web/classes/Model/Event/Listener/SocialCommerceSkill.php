<?php

namespace Model\Event\Listener ;

class SocialCommerceSkill extends Listener {
    
    public function Social_Commerce_Skill(\Model\Game\Trading\Skill $sell) {
        $char = $sell->character ;
        $user = $char->user ;      
        if ($user->mailon_commerce_skill && $user->email_valid) {
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_COMMERCE_SKILL_SUBJECT(),
                "" . \I18n::ONMAIL_COMMERCE_SKILL_MESSAGE(
                        $sell->skill->getName(),
                        $sell->instance->city->getName()
                        )
                ) ;
        }
    }
    
}
