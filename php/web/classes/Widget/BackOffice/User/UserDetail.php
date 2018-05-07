<?php

namespace Widget\BackOffice\User ;

class UserDetail extends \Quantyl\Answer\Widget {
    
    private $_user ;
    
    public function __construct(\Model\Identity\User $user) {
        $this->_user = $user ;
    }
    
    public function getIdentity(\Model\Identity\User $u) {
        
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::FIRST_NAME() . " :</strong> " . $u->first_name . "</li>" ;
        $res .= "<li><strong>" . \I18n::LAST_NAME() . " :</strong> " . $u->last_name . "</li>" ;
        $res .= "<li><strong>" . \I18n::BIRTHDAY() . " :</strong> " . \I18n::_date($u->birth) . "</li>" ;
        $res .= "<li><strong>" . \I18n::SEX() . " :</strong> " . $u->sex . "</li>" ;
        // $res .= "<li>" . new \Quantyl\XML\Html\A("/User/Edit/Identity", \I18n::CHANGE_IDENTITY()) . "</li>" ;
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Card2(\I18n::USER_IDENTITY(), $res);
    }
    
    public function getAddress(\Model\Identity\User $u) {
        
        $msg = "<p>" ;
        $msg .= $u->address . "<br/>" ;
        if ($u->address_compl != "") {
            $msg .= $u->address_compl . "<br/>" ;
        }
        $msg .= $u->code . " " ;
        $msg .= $u->city . "<br/>" ;
        $msg .= $u->state ;
        $msg .= "</p>" ;
        // $msg .= "<p>" . new \Quantyl\XML\Html\A("/User/Edit/Address", \I18n::CHANGE_ADDRESS()) . "</p>" ;
        
        return new \Answer\Widget\Misc\Card2(\I18n::USER_ADDRESS() , $msg);
    }
    
    public function getAccount(\Model\Identity\User $u) {
        
        $res = "<ul>" ;
        $auth = \Model\Identity\Authentication\Luchronia::GetFromUser($u) ;
        $res .= "<li>" . $auth->nickname . "</li>" ;
        $res .= "<li>" . $u->email . " " . ($u->email_valid ? \I18n::YES_ICO() : \I18n::NO_ICO() ) .  "</li>" ;
        $res .= "</ul>" ;
       
        return new \Answer\Widget\Misc\Card2(\I18n::USER_AUTHENTICATION(), $res);
    }
    
    public function getQuanta(\Model\Identity\User $u) {
        
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::QUANTAS() . " :</strong> " . $u->quanta . "</li>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/BackOffice/User/GiveQuanta?user={$u->id}", \I18n::BO_GIVE_QUANTA()) . "</li>" ;
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Card2(\I18n::USER_QUANTA(), $res);
    }
    
    public function getCharacters(\Model\Identity\User $user) {
        
        $res = "<h2>" . \I18n::CHARACTERS() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::AVATAR(),
            \I18n::IDENTITY(),
            \I18n::CONSTANTS(),
            \I18n::POSITION()
        )) ;
        
        foreach (\Model\Game\Character::GetFromUser($user) as $char) {
            $act = "<br/>" . new \Quantyl\XML\Html\A("/Game/Character/TakeControl?id={$char->id}", \I18n::CHARACTER_TAKE_CONTROL()) ;
            
            $table->addRow(array(
                $char->getImage("mini"),
                new \Quantyl\XML\Html\A("/BackOffice/Game/Character?id={$char->id}", $char->getName()) . "<br/>" .
                $char->race->getName() . " - " . $char->sex->getName() . "<br/>" .
                \I18n::LEVEL() . " : " . $char->level . "<br/>" .
                \I18n::METIERS() . " : " . $char->getHonorary() .
                $act,
                \I18n::TIME() . " : " . $char->getTime() . "<br/>" .
                \I18n::ENERGY() . " : " . $char->getEnergy() . "<br/>" .
                \I18n::HYDRATION() . " : " . $char->getHydration() . "<br/>" .
                \I18n::HEALTH() . " : " . $char->health,
                $char->position->getName()
            )) ;
        }
        $res .= $table ;
        
        return $res ;
    }
    
    public function getContent() {
        
        $res = "" ;
        
        $res .= $this->getIdentity($this->_user) ;
        $res .= $this->getAddress($this->_user) ;
        $res .= $this->getAccount($this->_user) ;
        $res .= $this->getQuanta($this->_user) ;
        
        $res .= $this->getCharacters($this->_user) ;
        
        return $res ;
        
    }
    
}
