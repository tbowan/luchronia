<?php

namespace Services\Game\Post ;

class ComposeGroup extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("post", new \Quantyl\Form\Model\Id(\Model\Game\Building\Post::getBddTable())) ;
    }

    public function getCity() {
        return $this->post->instance->city ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::COMPOSE_GROUP_MESSAGE($this->post->cost_mail)) ;
        $form->addInput("to_friend", new \Quantyl\Form\Fields\Boolean(\I18n::MAIL_TO_FRIEND())) ;
        $form->addInput("to_citizen", new \Quantyl\Form\Fields\Boolean(\I18n::MAIL_TO_CITIZEN())) ;
        $form->addInput("to_present", new \Quantyl\Form\Fields\Boolean(\I18n::MAIL_TO_PRESENT())) ;
        $form->addInput("title", new \Quantyl\Form\Fields\Text(\I18n::TITLE())) ;
        $form->addInput("content", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
        
    }

    public function addFriend($to_friend, &$recipient) {
        if ($to_friend) {
            foreach (\Model\Game\Social\Friend::GetFromA($this->getCharacter()) as $friend) {
                $recipient[$friend->b->id] = $friend->b ;
            }
        }
    }
    
    public function addCitizen($to_citizen, &$recipient) {
        if ($to_citizen) {
            foreach (\Model\Game\Character::GetFromCitizenship($this->getCity()) as $citizen) {
                $recipient[$citizen->id] = $citizen ;
            }
        }
    }
    
    public function addPresent($to_present, &$recipient) {
        if ($to_present) {
            foreach (\Model\Game\Character::GetPopulation($this->getCity()) as $present) {
                $recipient[$present->id] = $present ;
            }
        }
    }
    
    public function onProceed($data) {
        $me = $this->getCharacter() ;
        
        $recipients = array() ;
        $this->addFriend($data["to_friend"], $recipients) ;
        $this->addCitizen($data["to_citizen"], $recipients) ;
        $this->addPresent($data["to_present"], $recipients) ;
        
        if (isset($recipients[$me->id])) {
            unset($recipients[$me->id]) ;
        }
        
        if (count($recipients) == 0) {
            throw new \Exception(\I18n::EXP_COMPOSEGROUP_NO_RECIPIENTS()) ;
        }
        
        $cost = $this->post->cost_mail * count($recipients) ;
        if ($me->getCredits() < $cost) {
            throw new \Exception(\I18n::EXP_COMPOSEGROUP_CANNOT_AFFORD($cost)) ;
        }
        
        $me->addCredits( - $cost) ;
        $me->update() ;
        $this->post->instance->city->addCredits($cost) ;
        
        $mail = \Model\Game\Post\Mail::createFromValues(array(
            "author" => $me,
            "city"   => $me->position,
            "personnal" => true,
            "to_friend" => $data["to_friend"],
            "to_citizen" => $data["to_citizen"],
            "to_present" => $data["to_present"],
            "title" => $data["title"],
            "content" => $data["content"],
            "created" => time()
        )) ;
        
        foreach ($recipients as $rec) {
            $inbox = \Model\Game\Post\Inbox::createFromValues(array(
                "mail" => $mail,
                "box" => \Model\Game\Post\Mailbox::GetInboxFromCharacter($rec),
                "read" => false
            )) ;
            \Model\Event\Listening::Social_Mail_Inbox($inbox) ;
        }
        
        \Model\Game\Post\Inbox::createFromValues(array(
            "mail" => $mail,
            "box" => \Model\Game\Post\Mailbox::GetOutboxFromCharacter($me),
            "read" => true
        )) ;
        
        if ($this->referer != null) {
            $this->setAnswer(new \Quantyl\Answer\Redirect($this->referer)) ;
        }

    }
    
}
