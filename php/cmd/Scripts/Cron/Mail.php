<?php

namespace Scripts\Cron ;

class Mail extends \Scripts\Base {
    
    public function doStuff() {
        $cnt = 0 ;
        
        $next = \Model\Mail\Queue::getFirst() ;
        while ($next != null) {
            $cnt++ ;
            $this->sendMail($next) ;
            $next->delete() ;
            $next = \Model\Mail\Queue::getFirst() ;
        }
        
        echo "Sent count : " . $cnt . "\n" ;
    }
    
    public function sendMail(\Model\Mail\Queue $m) {
        
        echo "Sending mail :\n" ;
        echo " - Subject : ". $m->subject . "\n" ;
        echo " - From    : ". $m->from . "\n" ;
        echo " - To      : ". $m->to . "\n" ;

        
        $headers  = "MIME-Version: 1.0\r\n" ;
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        // $headers .= 'To: ' . $m->to . "\r\n";
        $headers .= 'From: ' . $m->from. "\r\n";
        
        $content = "<html>" ;
        $content .= "<head><title>" . $m->subject . "</title></head>" ;
        $content .= "<body>" . $m->content . "</body>" ;
        $content .= "</html>" ;
        
        // Send the mail
        return mail(
                $m->to,
                '=?utf-8?B?'.base64_encode($m->subject).'?=',
                $content,
                $headers
                ) ;
    }
    
}
