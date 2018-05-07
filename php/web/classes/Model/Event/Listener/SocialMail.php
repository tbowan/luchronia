<?php

namespace Model\Event\Listener ;

class SocialMail extends Listener {
    
    public function Social_Mail_Inbox(\Model\Game\Post\Inbox $inbox) {
        
        $char = $inbox->box->character ;
        $user = $char->user ;
        
        if ($user->mailon_mail && $user->email_valid) {
            
            $mail = $inbox->mail ;
            $author = $mail->author ;
            
            // Send email to user
            $res = \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                $user->email,
                "" . \I18n::ONMAIL_MAIL_SUBJECT(),
                "" . \I18n::ONMAIL_MAIL_MESSAGE(
                        $author->getName(),
                        $mail->title,
                        $mail->content)
                ) ;
            
        }
    }
    
}
