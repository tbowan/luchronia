<?php

namespace Services\Game\Post ;

class Compose extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("recipient", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable())) ;
        $form->addInput("prev", new \Quantyl\Form\Model\Id(\Model\Game\Post\Inbox::getBddTable(), "", false)) ;
        $form->addInput("referer", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(
                "<p><strong>" . \I18n::RECIPIENT() . " : </strong>" 
                . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$this->recipient->id}", $this->recipient->getName())
                . ".</p>") ;
        $title = $form->addInput("title", new \Quantyl\Form\Fields\Text(\I18n::TITLE())) ;
        $content = $form->addInput("content", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
        
        if ($this->prev != null) {
            $title->setValue(\I18n::MAIL_FW() . $this->prev->mail->title) ;
            $content->setValue(""
                    . "<p></p>"
                    . "<p>"
                        . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$this->prev->mail->author->id}", $this->prev->mail->author->getName())
                        . ", "
                        . \I18n::_date_time($this->prev->mail->created - DT)
                        . " :"
                        . "</p>"
                    . "<blockquote>" . $this->prev->mail->content . "</blockquote>") ;
        }
        
    }
    
    public function onProceed($data) {
        
        $me = $this->getCharacter() ;
        $him = $this->recipient ;
        
        $mail = \Model\Game\Post\Mail::createFromValues(array(
            "author" => $me,
            "city"   => $me->position,
            "personnal" => true,
            "to_friend" => false,
            "to_citizen" => false,
            "to_present" => false,
            "title" => $data["title"],
            "content" => $data["content"],
            "created" => time()
        )) ;
        
        \Model\Game\Post\Recipient::createFromValues(array(
            "character" => $him,
            "mail" => $mail
        )) ;
        
        $inbox = \Model\Game\Post\Inbox::createFromValues(array(
            "mail" => $mail,
            "box" => \Model\Game\Post\Mailbox::GetInboxFromCharacter($him),
            "read" => false
        )) ;
        
        \Model\Game\Post\Inbox::createFromValues(array(
            "mail" => $mail,
            "box" => \Model\Game\Post\Mailbox::GetOutboxFromCharacter($me),
            "read" => true
        )) ;
        
        \Model\Event\Listening::Social_Mail_Inbox($inbox) ;
        
        
        if ($this->referer != null) {
            $this->setAnswer(new \Quantyl\Answer\Redirect($this->referer)) ;
        }
    }
}
