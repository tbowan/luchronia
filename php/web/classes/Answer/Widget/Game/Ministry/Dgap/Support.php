<?php


namespace Answer\Widget\Game\Ministry\Dgap ;

class Support extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Politic\System $system, \Model\Game\Character $viewer,  $classes = "") {
        parent::__construct($system->type->getName(), "", "", $this->getRevolution($system, $viewer), $classes) ;
    }
    
    public function getRevolution(\Model\Game\Politic\System $system, \Model\Game\Character $viewer) {
        $r = \Model\Game\Politic\Revolution::GetFromSystem($system) ;
        
        $res = "<ul class=\"itemList\">" ;
        $res .= $this->getRevolutionMessage($r) ;
        $res .= $this->getRevolutionProposed($r, $viewer) ;
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getRevolutionMessage(\Model\Game\Politic\Revolution $r) {
        $char = $r->character ;
        
        $res = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $char->getImage("mini") . "</div>" ;
        $res .= "<div class=\"description\">" ;
            $res .= "<div class=\"name\">" . \I18n::CREATOR() . " : " . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()) . "</div>" ;
            $res .= "<div>" . $r->message . "</div>" ;
        $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    public function getRevolutionProposed(\Model\Game\Politic\Revolution $r, \Model\Game\Character $viewer) {
        
        $s = $r->proposed ;
        
        $name = $s->type->getName() ;
        if ($s->name != "") {
            $name .= " - " . $s->name ;
        }
        
        
        $support    = \Model\Game\Politic\Support::CountFromRevolution($r) ;
        $citizen    = \Model\Game\Character::CountUnlockedCitizen($s->city) ;
        $high       = ceil(2 * $citizen / 3.0) ;

        $delay = $s->end - time() ;
        
        $res  = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $s->type->getImage() . "</div>" ;
        $res .= "<div class=\"description\">" ;
        $res .= "<div class=\"name\">"
                . \I18n::PROPOSED_SYSTEM() . " : "
                . new \Quantyl\XML\Html\A("/Game/Ministry/System?system={$s->id}", $name)
                . "</div>" ;
        $res .= "<div>" . \I18n::SECRET_ID() . " : " . $r->secretid . "</div>" ;
        $res .= "<div>" . \I18n::SUPPORT() . " : $support / $high " . new \Quantyl\XML\Html\Meter(0, $high, $support) . "</div>"  ;
        $res .= "<div>" . \I18n::REMAINING_TIME() . " : " . \I18n::_time_delay($delay) . "</div>"  ;
        $res .= $this->getLinks($r, $viewer) ;
        $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    public function getLinks(\Model\Game\Politic\Revolution $r, \Model\Game\Character $viewer) {
        
        if (! $viewer->isCitizen($r->system->city)) {
            return "" ;
        } else if (\Model\Game\Politic\Support::isSupporting($r, $viewer)) {
            return "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Ministry/System/UnSupport?revolution={$r->id}", \I18n::DOUNSUPPORT()) . "</div>" ;
        } else {
            return "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Ministry/System/Support?revolution={$r->id}", \I18n::DOSUPPORT()) . "</div>" ;
        }
        
    }
    
    
}
