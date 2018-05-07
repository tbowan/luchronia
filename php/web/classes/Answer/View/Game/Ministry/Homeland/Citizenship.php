<?php

namespace Answer\View\Game\Ministry\Homeland ;

class Citizenship extends \Answer\View\Base {
    
    private $_city ;
    
    public function __construct($service, $city) {
        parent::__construct($service);
        $this->_city = $city ;
    }
    
    public function filterCitizenship(&$requests, &$invitations) {
        foreach (\Model\Game\Citizenship::GetWaitingAnswer($this->_city) as $citizenship) {
            if ($citizenship->isInvite) {
                $invitations[] = $citizenship ;
            } else {
                $requests[]    = $citizenship ;
            }
        }
        return ;
    }
    
    
    public function getName($character) {
        return ""
                . $character->getName() . " "
                . $character->getConnectionImg("icone-inline")
                . "(" . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$character->id}", \I18n::DETAILS()) . ")" ;
    }
    
    public function getType($character) {
        return ""
                . $character->race->getName() . " "
                . $character->sex->getName() . " "
                . \I18n::LEVEL() . " " . $character->level ;
    }
    
    public function getMetier($character) {
        return $character->getHonorary() ;
    }
    
    public function getSince($c) {
        return "<strong>" . \I18n::SINCE() . " : </strong>" . \I18n::_date_time($c->created - DT) ;
    }
    
    public function getList($list) {
        $res  = "<ul class=\"itemList\">" ;
        foreach ($list as $c) {
            $res .= "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $c->character->getImage("mini") . "</div>" ;
            $res .= "<div class=\"description\">"
                    . "<div class=\"name\">"   . $this->getName($c->character) . "</div>"
                    . "<div class=\"type\">"   . $this->getType($c->character) . "</div>"
                    . "<div class=\"metier\">" . $this->getMetier($c->character) . "</div>"
                    . "<div class=\"since\">"  . $this->getSince($c) . "</div>"
                    . "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Details?citizenship={$c->id}", \I18n::MANAGE()) . "</div>"
                    . "</div>" ;
            $res .= "</div></li>" ;
        }
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getRequests($citizenships, $classes = "") {
        return new \Answer\Widget\Misc\Section(
                \I18n::CITIZEN_REQUESTS(),
                new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Change?city={$this->_city->id}", \I18n::MANAGE()),
                "",
                $this->getList($citizenships),
                $classes) ;
    }

    public function getInvitations($invitations, $classes = "") {
        return new \Answer\Widget\Misc\Section(
                \I18n::CITIZEN_INVITATIONS(),
                new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Invite?city={$this->_city->id}", \I18n::NEW_PROPOSAL()),
                "",
                $this->getList($invitations),
                $classes) ;
    }
    
    public function getTplContent() {
        $requests = array() ;
        $invites  = array() ;
        $this->filterCitizenship($requests, $invites) ;
        
        return ""
                . $this->getRequests($requests, "card-1-2")
                . $this->getInvitations($invites, "card-1-2")
                ;
    }
    
}
