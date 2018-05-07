<?php

namespace Model\Event\Listener ;

class SocialWallPublication extends Listener {
    
    public function Social_Wall_Publication(\Model\Game\Social\Post $post) {
        $author  = $post->author ;
        $date    = $post->date;
        $content = $post->content;
        $access  = $post->access;
                
        $fellowers = \Model\Game\Social\Follower::GetFromA($author);
        
        foreach ($fellowers as $fell){
        
            $char = $fell->b ;            
            
            if ($access->hasCharacterAccess($char, $author)){
            
            
                $user = $char->user ;
                if ($user->mailon_wall && $user->email_valid) {

                    // Send email to user
                    $res = \Model\Mail\Queue::Queue(
                        \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                        $user->email,
                        "" . \I18n::ONMAIL_WALL_PUBLICATION_SUBJECT(),
                        "" . \I18n::ONMAIL_WALL_PUBLICATION_MESSAGE(
                                $author->getName(),
                                \I18n::_date_time($date-DT),
                                $content
                                )
                        ) ;
                }   
            }
        }
    }
    
}
