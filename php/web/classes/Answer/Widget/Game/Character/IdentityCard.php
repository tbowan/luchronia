<?php

namespace Answer\Widget\Game\Character ;

class IdentityCard extends \Answer\Widget\Misc\Section {
    
    public function getCitizenship(\Model\Game\Character $c) {
        $cities = array() ;
        foreach (\Model\Game\Citizenship::GetFromCitizen($c) as $citiz) {
            $cities[] = new \Quantyl\XML\Html\A("/Game/City?id={$citiz->city->id}", $citiz->city->getName()) ;
        }
        return implode(",", $cities) ;
    }
    
    public function __construct(\Model\Game\Character $c, \Model\Game\Character $viewer, $myself, $classes) {
        
        $pos = new \Quantyl\XML\Html\A("/Game/City?id={$c->position->id}", $c->position->getName()) ;
        $cit = $this->getCitizenship($c) ;
                
        $res = "" ;
        $res .= "<div class=\"card-1-4\">" ;
        $res .= $c->getImage("med") ;
        $res .= "</div>\n" ;
        
        $res .= "<div class=\"card-3-4\">" ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::NAME()     . " :</strong> " . $c->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::RACE()     . " :</strong> " . $c->race->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::SEX()      . " :</strong> " . $c->sex->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::METIERS()  . " :</strong> " . $c->getHonorary() . "</li>" ;
        $res .= "<li><strong>" . \I18n::LEVEL()    . " :</strong> " . $c->level . "</li>" ;
        
        $res .= "<li><strong>" . \I18n::POSITION()          . " :</strong> " . $pos . "</li>" ;
        $res .= "<li><strong>" . \I18n::CITIZENSHIP()       . " :</strong> " . $cit . "</li>" ;
        
        if ($myself) {
            $res .= $this->getMyself($c) ;
        } else {
            $res .= $this->getOther($c, $viewer) ;
        }
        
        $res .= "</ul>" ;
        
        $res .= "</div>\n" ;
        
            
        parent::__construct(\I18n::IDENTITY_CARD(), "", "", $res, $classes) ;
    }
    
    public function getMyself(\Model\Game\Character $c) {
        $res = "" ;
        if ($c->locked) {
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Character/Unlock", \I18n::UNLOCK_CHARACTER()) . "</li>" ;
        } else {
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Character/Lock", \I18n::LOCK_CHARACTER()) . "</li>" ;
        }
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Character/Repatriate", \I18n::REPATRIATE()) . "</li>" ;
        
        return $res ;
    }
    
    public function getFriendship(\Model\Game\Character $c, \Model\Game\Character $v) {
        $res = "" ;
        
        $request_been = \Model\Game\Social\Request::GetFromAB ($c, $v) ;
        $request_send = \Model\Game\Social\Request::GetFromAB ($v, $c) ;
        
        if (\Model\Game\Social\Friend::areFriends($c, $v)) {
            $res .= "<li>"
                    . \I18n::ARE_FRIEND_WITH($c->id, $c->id)
                    . "</li>" ;
        } else if ($request_been !== null) {
            $res .= "<li>"
                    . \I18n::ALREADY_BEEN_REQUESTED($request_been->id)
                    . "</li>" ;
        } else if ($request_send !== null) {
            $res .= "<li>"
                    . \I18n:: ALREADY_REQUESTED($request_send->id)
                    . "</li>" ;
        } else {
            $res .= "<li>"
                    . \I18n::ARENT_FRIEND_WITH($c->id)
                    . "</li>" ;
        }
        return $res ;
    }
    
    public function getFollowing(\Model\Game\Character $c, \Model\Game\Character $v) {
        $following = \Model\Game\Social\Follower::GetFromAB($v, $c) ;
        if ($following !== null) {
            return "<li>"
                    . new \Quantyl\XML\Html\A("/Game/Character/Friend/UnFollow?id={$following->id}", \I18n::CANCEL_FOLLOWING())
                    . "</li>" ;
        } else {
            return "<li>"
                    . new \Quantyl\XML\Html\A("/Game/Character/Friend/Follow?id={$c->id}", \I18n::FOLLOW())
                    . "</li>" ;
        }
    }
    
    public function getOther(\Model\Game\Character $c, \Model\Game\Character $v) {
        
        $res = "" ;
        $res .= $this->getFriendship($c, $v) ;
        $res .= $this->getFollowing($c, $v) ;
        
        if ($c->locked) {
            $res .= "<li><strong>" . \I18n::CHARACTER_LOCK_STATE() . " :</strong> "
                    . \I18n::CHARACTER_LOCKED()
                    . "</li>" ;
        }
        return $res ;
    }

}
