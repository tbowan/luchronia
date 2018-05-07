<?php

namespace Answer\Widget\Game\Social ;

class Mailboxes extends \Answer\Widget\Misc\Section{
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::NAME(),
            \I18n::MAILS(),
            \I18n::UNREADS()
        )) ;
        
        foreach (\Model\Game\Post\Mailbox::GetFromCharacter($me) as $mailbox) {
            $total = \Model\Game\Post\Inbox::CountFromMailbox($mailbox) ;
            $unread = \Model\Game\Post\Inbox::CountUnreadFromMailbox($mailbox) ;
            $row = $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Post/Mailbox?mailbox={$mailbox->id}", $mailbox->getName()),
                $total,
                $unread
            )) ;
                
            if ($unread > 0) {
                $row->setAttribute("class", "unread") ;
            }
        }
        
        parent::__construct(
                \I18n::MY_MAIL_BOXES(),
                new \Quantyl\XML\Html\A("/Game/Post/ChoseRecipient?next=%2FGame%2FPost%2FCompose", \I18n::NEW_MAIL()),
                "",
                "$table",
                $classes);
    }
    
}
