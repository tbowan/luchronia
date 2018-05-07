<?php

namespace Answer\Widget\Game\Post ;

class Mailbox extends \Quantyl\Answer\Widget{
    
    private $_mb ;
    
    public function __construct(\Model\Game\Post\Mailbox $mb) {
        parent::__construct();
        $this->_mb = $mb ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::MAILS_OF_BOX() . "</h2>" ;
        
        $res .= new \Quantyl\XML\Html\A("/Game/Post/ChoseRecipient?next=%2FGame%2FPost%2FCompose", \I18n::SEND_NEW_MAIL()) ;
        
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SENDER(),
            \I18n::DATE(),
            \I18n::TITLE(),
            ""
        )) ;
        
        foreach (\Model\Game\Post\Inbox::GetFromMailbox($this->_mb) as $inbox) {
            $mail = $inbox->mail ;
            $row = $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$mail->author->id}", $mail->author->getName()),
                \I18n::_date_time($mail->created - DT),
                new \Quantyl\XML\Html\A("/Game/Post/Mail?inbox={$inbox->id}", $mail->title),
                new \Quantyl\XML\Html\A("/Game/Post/DelMail?inbox={$inbox->id}", \I18n::DELETE())
            )) ;
                
            if (! $inbox->read) {
                $row->setAttribute("class", "unread") ;
            }
        }
        
        $res .= $table ;
        
        return $res ;
        
    }
    
}


