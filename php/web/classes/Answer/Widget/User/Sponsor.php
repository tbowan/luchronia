<?php

namespace Answer\Widget\User ;

class Sponsor extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Identity\User $u, $classes = "") {
        parent::__construct(\I18n::USER_SPONSORING(), "", "",
                self::getSponsors($u)
                . self::getProteges($u),
                $classes
                );
    }
    
    public static function getSponsors(\Model\Identity\User $u) {
        
        $sponsors = array() ;
        
        foreach (\Model\Identity\Sponsor::GetFromProtege($u) as $p) {
            $char = $p->sponsor->character ;
            $sponsors[] = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()) ;
        }
        
        
        if (count($sponsors) == 0) {
            return "" ;
        } else if (count($sponsors) == 1) {
            return \I18n::YOUR_SPONSOR($sponsors[0]) ;
        } else {
            $list = "<ul>" ;
            foreach ($sponsors as $link) {
                $list .= "<li>$link</li>" ;
            }
            $list .= "</ul>" ;
            return \I18n::YOUR_SPONSORS() . $list ;
        }
        
    }
    
    public static function getProteges(\Model\Identity\User $u) {
        
        $proteges = array() ;
        $invitations = array() ;
        
        foreach (\Model\Identity\Sponsor::GetFromSponsor($u) as $p) {
            if ($p->protege == null) {
                $invitations[] = "<li>" . $p->getName() . "</li>" ;
            } else {
                $char = $p->protege->character ;
                $proteges[] = "<li>" . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()) . "</li>" ;
            }
        }
        
        $list_p = "<ul>" . implode("", $proteges) . "</ul>" ;
        $list_i = "<ul>" . implode("", $invitations) . "</ul>" ;
        
        $res = "" ;
        
        if (count($proteges) > 0) {
            $res .= \I18n::YOUR_PROTEGES() . $list_p ;
        }
        
        if (count($invitations) > 0) {
            $res .= \I18n::YOUR_INVITATIONS() . $list_i ;
        }
        
        if ($res == "") {
            $res .= \I18n::YOU_HAVENT_PROTEGES() ;
        }
        
        $res .= \I18n::CREATE_SPONSOR() ;
        
        return $res ;
    }
    
}
