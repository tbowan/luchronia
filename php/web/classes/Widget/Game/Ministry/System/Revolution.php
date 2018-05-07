<?php

namespace Widget\Game\Ministry\System ;

class Revolution extends Base {
    
    public function declaredContent(\Model\Game\Politic\Revolution $revolution) {
        $support = \Model\Game\Politic\Support::CountFromRevolution($revolution) ;
        $citizen = \Model\Game\Character::CountCitizen($this->_system->city) ;
        $target = ceil(2.0 * $citizen / 3.0) ;
        
        $res  = "<li><strong>" . \I18n::CURRENT_SUPPORT()   . " :</strong> " . $support . "</li>" ;
        $res .= "<li><strong>" . \I18n::TARGET_SUPPORT()    . " :</strong> " . $target . "</li>" ;
        $res .= "<li><strong>" . \I18n::REVOLUTION_UNTIL()       . " :</strong> " . \I18n::_date_time($this->_system->end) . "</li>" ;
        
        return $res ;
    }
    
    public function getSupport($revolution) {
        $mine = \Model\Game\Politic\Support::GetFromCharacter($this->_character) ;

        if ($mine == null || ! $mine->revolution->equals($revolution)) {
            // Can support
            return \I18n::REVOLUTION_CAN_SUPPORT($revolution->id) ;
        } else {
            // Ne plus supporter
            return \I18n::REVOLUTION_CAN_UNSUPPORT($revolution->id) ;
        }
    }
    
    public function getManifest(\Model\Game\Politic\Revolution $rev) {
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $rev->character->getImage("mini") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-center\">" ;
            $res .= "<div class=\"post-head\">" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$rev->character->id}", $rev->character->getName()) ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"post-content\">" ;
            $res .= $rev->message ;
            $res .= "</div>" ;
        $res .= "</div>" ;
        
        $res .= "</div>" ;
        return $res ;
    }
    
    public function getSpecific() {
        
        $revolution = \Model\Game\Politic\Revolution::GetFromSystem($this->_system) ;
        
        $res  = "<h3>" . \I18n::REVOLUTION_MANIFEST() . "</h3>" ;
        $res .= $this->getManifest($revolution) ;
        
        $res .= "<h3>" . \I18n::REVOLUTION_STATUS() . "</h3>" ;
        
        $status = $revolution->getStatus() ;
        
        $p = $revolution->proposed ;
        $proposed = new \Quantyl\XML\Html\A("/Game/Ministry/System/?system={$p->id}", $p->type->getName()) ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::REVOLUTION_PROPOSED() . " :</strong> " . $proposed . "</li>" ;
        $res .= "<li><strong>" . \I18n::REVOLUTION_STATUS() . " :</strong> " . $status->getName() . "</li>" ;
        
        if ($status->equals(\Model\Game\Politic\RevolutionStatus::Declared())) {
            $res .= $this->declaredContent($revolution) ;
        }
        
        $res .= "</ul>" ;
        
        $res .= $this->getSupport($revolution) ;
        
        return $res ;
    }
    
    public function getQuestion() {
        return"" ;
    }
    
    public function getGovernment() {
        return "" ;
    }
}
