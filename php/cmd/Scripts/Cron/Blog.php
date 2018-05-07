<?php

namespace Scripts\Cron ;

class Blog extends \Scripts\Base {
    
    public function doStuff() {
        
        $cnt = 0 ;
        
        foreach (\Model\Blog\Post::GetNewPublications() as $post) {
            echo "Publishing : " . $post->title . "\n" ;
            \Model\Event\Listening::Blog_Published($post) ;
            $post->notified = time() ;
            $post->update() ;
            $cnt++ ;
        }
        echo "Published : $cnt\n" ;
    }
    
}
