<?php

namespace Answer\Widget\Help\Skill;

class Learning extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Skill\Skill $s, $viewer, $classes = "") {
        
        $res = "" ;
        $res = "<ul>" ;
        $res .= $this->_getSkill($s) ;
        $res .= $this->_getParchment($s) ;
        $res .= $this->_getBook($s) ;
        $res .= $this->_getCost($s) ;
        $res .= "</ul>" ;
        
        $res .= $this->_getCharacter($s, $viewer) ;
        
        parent::__construct(\I18n::SKILL_LEARNING_TITLE(), "", "", $res, $classes);
    }
    
    private function _getSkill($s) {
        $learn = \Model\Game\Skill\Learn::GetFromCharacteristic($s->characteristic) ;
        return "<li><strong>" . \I18n::LEARNING_SKILL() . " : </strong>"
                        . $learn->skill->getImage("icone-inline")
                        . new \Quantyl\XML\Html\A("/Help/Skill?id={$learn->skill->id}", $learn->skill->getName())
                        . "</li>" ;
    }
    
    private function _getParchment($s) {
        $parch = \Model\Game\Ressource\Parchment::GetBySkill($s) ;
        if ($parch != null) {
            return "<li><strong>" . \I18n::PARCHMENT() . " : </strong>"
                        . $parch->item->getImage("icone-inline")
                        . new \Quantyl\XML\Html\A("/Help/Skill?id={$parch->item->id}", $parch->item->getName())
                        . "</li>" ;
        } else {
            return "" ;
        }
    }
    
    private function _getBook($s) {
        $book = \Model\Game\Ressource\Book::GetBySkill($s) ;
        if ($book != null) {
            return "<li><strong>" . \I18n::BOOK() . " : </strong>"
                        . $book->item->getImage("icone-inline")
                        . new \Quantyl\XML\Html\A("/Help/Skill?id={$book->item->id}", $book->item->getName())
                        . "</li>" ;
        } else {
            return "" ;
        }
    }
    
    private function _getCost($s) {
        if ($s->cost == 0) {
            return "<li><strong>" . \I18n::BUY() . " : </strong>"
                        . \I18n::INNATE_SKILL()
                        . "</li>" ;
        } else {
            return "<li><strong>" . \I18n::BUY() . " : </strong>"
                        . $s->cost . " " . \I18n::LEVEL_POINT()
                        . "</li>" ;
        }
    }
    
    private function _getCharacter($s, $viewer) {
        if ($viewer === null) {
            return "" ;
        } else {
            
            $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($viewer, $s) ;
            if ($cs === null) {
                $level    = 0 ;
                $learning = 0 ;
                $next     = \Model\Game\Skill\Character::getLevelThreshold(1) ;
                $buy      = new \Quantyl\XML\Html\A("/Game/LevelUp/NewSkill?skill={$s->id}", \I18n::BUY()) ;
            } else {
                $level    = $cs->level ;
                $learning = $cs->learning ;
                $next     = $cs->getLearningThreshold() ;
                $cost     = ceil(($cs->getLearningThreshold() - $cs->learning) / 100.0) ;
                $buy      = new \Quantyl\XML\Html\A("/Game/LevelUp/Skill?cs={$cs->id}&pt={$cost}", \I18n::SKILL_INCREASE($cost)) ;
            }
            $res = "<h2>" . $viewer->getName() . "</h2>" ;
            $res .= "<ul>" ;
            $res .= "<li><strong>" . \I18n::LEVEL()     . " : </strong>" . $level . "</li>" ;
            $res .= "<li><strong>" . \I18n::LEARNING()  . " : </strong>" . $learning . " / " . $next . " " . new \Quantyl\XML\Html\Meter(0, $next, $learning) . "</li>" ;
            $res .= "<li><strong>" . \I18n::BUY_LEVEL() . " : </strong>" . $buy . "</li>" ; 
            $res .= "</ul>" ;
            
            return $res ;
            
        }
    }
    
}


        
        