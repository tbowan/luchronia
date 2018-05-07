<?php

namespace Answer\Widget\Game\Ministry\Dgap ;

class System extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Politic\System $system, $canmanage, $classes = "") {
        
        $type = $system->type->getValue() ;
        $msg = "<ul class=\"itemList\">" ;
        $msg .= $this->base($system) ;
        $msg .= $this->$type($system) ;
        $msg .= "</ul>" ;
        
        $more = array() ;
        if ($system->id != 0) {
            $more[] = new \Quantyl\XML\Html\A("/Game/Ministry/System?system={$system->id}", \I18n::DETAILS()) ;
        }
        if ($canmanage) {
            $more[] = new \Quantyl\XML\Html\A("/Game/Ministry/System/Change?city={$system->city->id}", \I18n::CHANGE()) ;
        }
        parent::__construct(\I18n::POLITICAL_SYSTEM(), implode(", ", $more), "", $msg, $classes);
    }
    
    
    public function base(\Model\Game\Politic\System $s) {
        $res  = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $s->type->getImage() . "</div>" ;
        $res .= "<div class=\"description\">" ;
        
        $res .= "<div class=\"name\">" . $s->type->getName() ;
        if ($s->name != "") {
            $res . " - " . $s->name ;
        }
        $res .= "</div>" ;
        
        $res .= "<div>" . \I18n::CITY() . " : " . new \Quantyl\XML\Html\A("/Game/City?id={$s->city->id}", $s->city->getName()). "</div>" ;
        $res .= "<div>" . \I18n::START_DATE() . " : " . ($s->start == null ? "-" : \I18n::_date_time($s->start - DT)) . "</div>" ;
        $res .= "<div>" . \I18n::END_DATE() . " : " . ($s->end == null ? "-" : \I18n::_date_time($s->end - DT)) . "</div>" ;
        
        
        $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    // Anarchy : Nothing to display
    public function Anarchy(\Model\Game\Politic\System $s) {
        return "" ;
    }
    
    // Monarchy :  Who's the king
    public function Monarchy(\Model\Game\Politic\System $s) {
        $monarchy   = \Model\Game\Politic\Monarchy::GetFromSystem($s) ;
        $character  = $monarchy->king ;
        return new \Answer\Widget\Game\Social\CharacterAsItem($character, \I18n::KING() . " : ") ;
    }
    
    // Senate
    public function Senate(\Model\Game\Politic\System $s) {
        
    }
    
    
    // Republic : president, pres, sys
    
    public function Republic(\Model\Game\Politic\System $s) {
        $res = "" ;
        $republic   = \Model\Game\Politic\Republic::GetFromSystem($s) ;
        $president  = \Model\Game\Politic\President::GetLastFromRepublic($republic) ;
        $character  = $president->character ;
        $res .= new \Answer\Widget\Game\Social\CharacterAsItem($character, \I18n::PRESIDENT() . " : ") ;
        $res .= $this->getQuestionVote("", \I18n::PRESIDENT(), $republic->pres_delay, $republic->pres_type, $republic->pres_point) ;
        $res .= $this->getQuestionBool("", \I18n::SYSTEM_CHANGE(), $republic->sys_delay, $republic->sys_quorum, $republic->sys_threshold) ;
        
        return $res ;
    }
    
    public function Parliamentary(\Model\Game\Politic\System $s) {
        
    }
    
    public function Democracy(\Model\Game\Politic\System $s) {
        
    }
    
    public function Revolution(\Model\Game\Politic\System $s) {
        
    }
    
    // Misc helper function
    
    public function getQuestionVote($img, $name, $delay, $type, $point) {
        $res = "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $img . "</div>" ;
            $res .= "<div class=\"description\">" ;
                $res .= "<div class=\"name\">" . $name . "</div>" ;
                $res .= "<div>" . \I18n::DELAY() . " : " . $delay . " " . \I18n::DAYS() . "</div>" ;
                $res .= "<div>" . \I18n::VOTE_TYPE() . " : " . $type->getName() . "</div>" ;
                $res .= "<div>" . \I18n::VOTE_POINT() . " : " . $point . "</div>" ;
            $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    public function getQuestionBool($img, $name, $delay, $quorum, $threshold) {
        $res = "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $img . "</div>" ;
            $res .= "<div class=\"description\">" ;
                $res .= "<div class=\"name\">" . $name . "</div>" ;
                $res .= "<div>" . \I18n::DELAY() . " : " . $delay . " " . \I18n::DAYS() . "</div>" ;
                $res .= "<div>" . \I18n::QUORUM() . " : " . (100 * $quorum) . " %</div>" ;
                $res .= "<div>" . \I18n::THRESHOLD() . " : " . (100 * $threshold) . " %</div>" ;
            $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
}
