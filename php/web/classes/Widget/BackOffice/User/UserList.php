<?php

namespace Widget\BackOffice\User ;

class UserList extends \Quantyl\Answer\Widget {
    
    private $_admin ;
    
    public function __construct(\Model\Identity\User $admin) {
        parent::__construct();
        $this->_admin = $admin ;
    }
    
    public function getContent() {
        return ""
                . $this->getSupports()
                . $this->getUsers() ;
    }
    
    public function getSupports() {
        $res = "<h2>" . \I18n::INVITATION_REQUESTS() . "</h2>" ;
        
        $res .= "<p>" ;
        $res .= new \Quantyl\XML\Html\A("/BackOffice/User/Invite", \I18n::INVITE_USER()) ;
        $res .= "</p>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::EMAIL(),
            \I18n::CODE(),
            \I18n::DATE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Identity\Sponsor::getRequest() as $r) {
            $table->addRow(array(
                $r->mail == null ? "-" : $r->mail,
                $r->code,
                \I18n::_date_time($r->date),
                new \Quantyl\XML\Html\A("/BackOffice/User/Accept?sponsor={$r->id}", \I18n::MANAGE_REQUEST())
            )) ;
        }
        
        foreach (\Model\Identity\Sponsor::GetFromSponsor($this->_admin) as $r) {
            if ($r->protege == null) {
                $table->addRow(array(
                    $r->mail == null ? "-" : $r->mail,
                    $r->code,
                    \I18n::_date_time($r->date),
                    ($r->mail != null ? new \Quantyl\XML\Html\A("/BackOffice/User/Reinvite?sponsor={$r->id}", \I18n::REINVITE_REQUEST()) : "-")
                )) ;
            }
        }
        
        
        $res .= $table ;
        
        return $res ;
    }
    
    public function getUsers() {
        
        $res = "<h2>" . \I18n::USER_LIST() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::IDENTITY(),
            \I18n::CHARACTERS(),
            \I18n::LAST_ACTIVITY(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Identity\User::GetAll() as $u) {
            $cs = array() ;
            $last = 0 ;
            foreach (\Model\Game\Character::GetFromUser($u) as $c) {
                $cs[] = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", $c->getName()) ;
                $last = max($last, $c->last) ;
            }
            $table->addRow(array(
                $u->first_name . " " . $u->last_name,
                implode("<br/>", $cs),
                ($last == 0 
                        ? \I18n::NEVER() 
                        : \I18n::_date_time($last)),
                new \Quantyl\XML\Html\A("/BackOffice/User/Show?id={$u->id}", \I18n::DETAILS())
            )) ;
        }
        $res .= $table ;
        return $res ;
        
    }
    
}
