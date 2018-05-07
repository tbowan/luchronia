<?php

namespace Answer\Widget\Game\Post ;

class Mail extends \Quantyl\Answer\Widget{
    
    private $_ib ;
    private $_me ;
    
    public function __construct(\Model\Game\Post\Inbox $ib, \Model\Game\Character $me) {
        parent::__construct();
        $this->_ib = $ib ;
        $this->_me = $me ;
    }
    
    public function getContent() {
        $res = "<div class=\"mail\">" ;
        
        $res .= "<div class=\"sender\">" ;
        $res .= "<h3>" . \I18n::SENDER() . "</h3>" ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::AUTHOR() . " : </strong>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$this->_ib->mail->author->id}", $this->_ib->mail->author->getName()) ;
        $res .= "</li>" ;
        $res .= "<li><strong>" . \I18n::CITY() . " : </strong>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/City?id={$this->_ib->mail->city->id}", $this->_ib->mail->city->getName()) ;
        $res .= "</li>" ;
        $res .= "<li><strong>" . \I18n::DATE() . " : </strong>" ;
        $res .= \I18n::_date_time($this->_ib->mail->created - DT);
        $res .= "</li>" ;
        
        if (! $this->_ib->mail->personnal) {
            $res .= "<li>" . \I18n::MAIL_IS_OFFICIAL() . "</li>" ;
        }
        
        $res .= "</ul>" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"recipient\">" ;
        $res .= "<h3>" . \I18n::RECIPIENT() . "</h3>" ;
        
        $res .= "<ul>" ;
        foreach (\Model\Game\Post\Recipient::GetFromMail($this->_ib->mail) as $recipient) {
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$recipient->character->id}", $recipient->character->getName()) . "</li>" ;
        }
        
        if ($this->_ib->mail->to_friend) {
            $res .= "<li>"
                    . "<strong>" . \I18n::FRIEND_OF() . " : </strong>" 
                    . new \Quantyl\XML\Html\A(
                            "/Game/Character/Show?id={$this->_ib->mail->author->id}",
                            $this->_ib->mail->author->getName())
                    . "</li>" ;
        }
        
        if ($this->_ib->mail->to_citizen) {
            $res .= "<li>"
                    . "<strong>" . \I18n::CITIZEN_OF() . " : </strong>" 
                    . new \Quantyl\XML\Html\A(
                            "/Game/City?id={$this->_ib->mail->city->id}",
                            $this->_ib->mail->city->getName())
                    . "</li>" ;
        }

        if ($this->_ib->mail->to_present) {
            $res .= "<li>"
                    . "<strong>" . \I18n::POPULATION_OF() . " : </strong>" 
                    . new \Quantyl\XML\Html\A(
                            "/Game/City?id={$this->_ib->mail->city->id}",
                            $this->_ib->mail->city->getName())
                    . "</li>" ;
        }
        $res .= "</ul>" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"title\">" ;
        $res .= "<p><strong>" . \I18n::TITLE() . " : </strong>" ;
        $res .= $this->_ib->mail->title ;
        $res .= "</p>" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"content\">" ;
        $res .= $this->_ib->mail->content ;
        $res .= "</div>" ;
        
        $res .= "</div>" ;
        
        if (! $this->_me->equals($this->_ib->mail->author)) {
            $res .= "<p>" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Post/Compose?recipient={$this->_ib->mail->author->id}&prev={$this->_ib->id}", \I18n::MAIL_REPLY()) ;
            $res .= "</p>" ;
        }
        
        return $res ;
    }
    
    
}
