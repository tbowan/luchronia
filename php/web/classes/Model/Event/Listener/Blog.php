<?php


namespace Model\Event\Listener ;

class Blog extends Listener {
    
    public function Blog_Published(\Model\Blog\Post $post) {
        foreach (\Model\Identity\User::GetNotifiable("mailon_blog") as $user) {
            $this->sendNotification($post, $user) ;
        }
    }
    
    public function sendNotification(\Model\Blog\Post $post, \Model\Identity\User $user) {
        \Model\Mail\Queue::Queue(
            \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
            $user->email,
            "" . \I18n::ONMAIL_BLOG_SUBJECT($post->title),
            "" . \I18n::ONMAIL_BLOG_MESSAGE(
                    new \Answer\Widget\Blog\Post($post)
                    )
            ) ;
    }
}
