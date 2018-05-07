<?php

namespace Quantyl\Misc ;

class Mail {
    
    private $_from ;
    private $_to ;
    private $_subject ;
    private $_content ;
    
    public function __construct($from, $to, $subject, $message) {
        $this->_from = $from;
        $this->_to = $to ;
        $this->_subject = $subject ;
        $this->_content = $message ;
    }
    
    public function send() {
        $headers  = "MIME-Version: 1.0\r\n" ;
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= 'To: ' . $this->_to . "\r\n";
        $headers .= 'From: ' . $this->_from. "\r\n";
        // Send the mail
        return mail(
                $this->_to,
                '=?utf-8?B?'.base64_encode($this->_subject).'?=',
                $this->_content,
                $headers
                ) ;
    }
    
    public static function sendmail($from, $to, $subject, $message) {
        $mail = new Mail($from, $to, $subject, $message) ;
        return $mail->send() ;
    }
    
}
