<?php

namespace Services\User\Create ;

class StepF extends CreateStep {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CREATE_USER_MESSAGE()) ;
        
        $auth = $form->addInput("auth", new \Quantyl\Form\FieldSet(\I18n::IDENTIFICATION())) ;
        $auth->setMessage(\I18n::AUTHENTICATION_MESSAGE()) ;
        $auth->addInput("nickname", new \Quantyl\Form\Fields\Text    (\I18n::NICKNAME(), true)) ;
        $auth->addInput("password", new \Quantyl\Form\Fields\Password(\I18n::PASSWORD(), true)) ;
        $auth->addInput("password_bis", new \Quantyl\Form\Fields\Password(\I18n::PASSWORD_BIS(), true)) ;
        
        $civil = $form->addInput("civil", new \Quantyl\Form\FieldSet(\I18n::CIVIL_INFORMATION())) ;
        $civil->setMessage(\I18n::CIVIL_INFORMATION_MESSAGE()) ;
        $civil->addInput("first_name", new \Quantyl\Form\Fields\Text(\I18n::FIRSTNAME(), true)) ;
        $civil->addInput("last_name", new \Quantyl\Form\Fields\Text(\I18n::LASTNAME(), true)) ;
        $civil->addInput("birthday", new \Quantyl\Form\Fields\Date(\I18n::BIRTHDAY(), true)) ;
        
        $civil->addInput("email", new \Quantyl\Form\Fields\Email(\I18n::EMAIL(), true))
              ->setValue($this->code);
        
        $last = \Model\Identity\Cgvu::getLast() ;
        if ($last != null) {
            $cgvu = $form->addInput("cgvu", new \Quantyl\Form\FieldSet(\I18n::CGVU())) ;
            $cgvu->setMessage(\I18n::CGVU_MESSAGE()) ;
            $cgvu->addInput("accepted", new \Quantyl\Form\Fields\Boolean(\I18n::ACCEPT_CGVU($last->file), true)) ;
        }
        
        return $form ;
    }
    
    public function addSubmit(\Quantyl\Form\Form &$form) {
        $form->addSubmit("prev", new \Quantyl\Form\Fields\Submit(\I18n::PREV_STEP(), false))
             ->setCallBack($this, "onPrev") ;
        $form->addSubmit("register", new \Quantyl\Form\Fields\Submit(\I18n::REGISTER_STEP()))
             ->setCallBack($this, "onRegister") ;
    }
    
    public function checkAvatar() {
        $r = $this->race ;
        $s = $this->sex ;
        
        foreach ($this->avatar as $id => $item) {
            $layer = \Model\Game\Avatar\Layer::GetById($id) ;
            if (
                    $item != null && (
                    ($item->race != null && !  $item->race->equals($r)) ||
                    ($item->sex  != null && !  $item->sex->equals($s) ) ||
                    (                       ! $item->layer->equals($layer)     ) ||
                    ($item->price > 0                                          )
                    )
                ) {
                return false ;
            }
        }
        return true ;
    }
    
    public function onRegister($data) {
        
        // check data
        $params = $this->urlencodeParams() ;
        
        if ($this->race == null) {
            return new \Quantyl\Answer\Redirect("/User/Create/Step1?$params") ;
        } else if ($this->sex == null) {
            return new \Quantyl\Answer\Redirect("/User/Create/Step2?$params") ;
        } else if ($this->name == null) {
            return new \Quantyl\Answer\Redirect("/User/Create/Step4?$params") ;
        } else if ($this->position == null) {
            return new \Quantyl\Answer\Redirect("/User/Create/Step5?$params") ;
        } else if (! $this->checkAvatar()) {
            return new \Quantyl\Answer\Redirect("/User/Create/Step3?$params") ;
        }
        
        // Check passwords
        if ($data["auth"]["password"] != $data["auth"]["password_bis"]) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::PASSWORD_DONT_MATCH()) ;
        }
        
        // check user already exists
        $already = \Model\Identity\Authentication\Luchronia::GetByName($data["auth"]["nickname"]) ;
        if ($already !== null) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::NICKNAME_ALREADY_TAKEN()) ;
        }
        
        // create user
        $user = new \Model\Identity\User() ;
        $user->email        = $data["civil"]["email"] ;
        $user->first_name   = $data["civil"]["first_name"] ;
        $user->last_name    = $data["civil"]["last_name"] ;
        $user->birth        = $data["civil"]["birthday"] ;
        $user->create() ;
        
        $user->generateToken() ;
        $user->sendMailCheck() ;
        
        $last = \Model\Identity\Cgvu::getLast() ;
        if ($last != null) {
            \Model\Identity\Accepted::createFromValues(array(
                "user" => $user,
                "cgvu" => $last,
                "ip" => $this->getRequest()->getServer()->getClientIp(),
                "when" => time()
            )) ;
        }
        
        // create authentication
        $auth = \Model\Identity\Authentication\Luchronia::Register(
                $user,
                $data["auth"]["nickname"],
                $data["auth"]["password"]
                ) ;
        
        // create character
        $character = new \Model\Game\Character() ;
        $character->name     = $this->name ;
        $character->user     = $user ;
        $character->race     = $this->race ;
        $character->sex      = $this->sex ;
        $character->position = $this->position ;
        $character->since    = time() ;
        $character->create() ;
        
        $user->character = $character ;
        $user->update() ;
        
        // Sponsoring
        foreach (\Model\Identity\Sponsor::getByUserCode($this->code) as $sponsor) {
            $sponsor->protege = $user ;
            $sponsor->update() ;
            
            \Model\Game\Social\Friend::createFromValues(array(
                "a" => $sponsor->sponsor->character,
                "b" => $character
            )) ;
        }
        
        // Create Avatar
        foreach ($this->avatar as $id => $item) {
            if ($item != null) {
                \Model\Game\Avatar\Used::createFromValues(array(
                    "item"      => $item,
                    "character" => $character
                )) ;
            }
        }
        
        // Create mailboxes
        \Model\Game\Post\Mailbox::createFromValues(array(
            "character" => $character,
            "name" => "",
            "type" => \Model\Game\Post\Type::INBOX()
        )) ;
        
        \Model\Game\Post\Mailbox::createFromValues(array(
            "character" => $character,
            "name" => "",
            "type" => \Model\Game\Post\Type::OUTBOX()
        )) ;
        
        // set in session
        session_regenerate_id(true) ;
        $_SESSION["auth"] = $auth ;
        $_SESSION["user"] = $user ;
        $_SESSION["char"] = $character ;
        
        // redirect to congratulation page
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game")) ;
        
        // Send mail to admins
        \Model\Mail\Queue::Queue(
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                \Model\Quantyl\Config::ValueFromKey("INVITATION_SRCMAIL", ""),
                \I18n::USER_CREATION_SUBJECT($character->getName()),
                \I18n::USER_CREATION_MESSAGE()
                ) ;
        
    }

    public function createData($data) {
        return ;
    }

    public function getNextUrl() {
        return "" ;
    }
    
    
    public function getPrevUrl() {
        return "/User/Create/Step5" ;
    }

}
