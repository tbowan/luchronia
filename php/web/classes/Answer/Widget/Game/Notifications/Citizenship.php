<?php

namespace Answer\Widget\Game\Notifications ;

class Citizenship extends Base {
    
    public function __construct($invitations) {
        
        $res = "<ul>" ;
        foreach ($invitations as $inv) {
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Details?citizenship={$inv->id}", $inv->city->getName()) . "</li>";
        }
        $res .= "</ul>" ;
        
        parent::__construct(
                \I18n::NOTIFICATION_CITIZENSHIP_TITLE(),
                \I18n::NOTIFICATION_CITIZENSHIP_MESSAGE($res));
    }

    public function getClasses() {
        return parent::getClasses() . " notice" ;
    }
    
    public static function getNotif(\Model\Game\Character $c) {
        $invites = array() ;
        foreach (\Model\Game\Citizenship::GetInivtations($c) as $inv) {
            $invites[] = $inv ;
        }
        if (count($invites) == 0) {
            return "" ;
        } else {
            return new Citizenship($invites) ;
        }
    }
    
}
