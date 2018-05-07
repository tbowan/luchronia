<?php

namespace Answer\Widget\Help\Skill ;

class SkillAsItem extends \Quantyl\Answer\Widget {
    
    private $_skill ;
    private $_viewer ;
    private $_cs ;
    private $_class ;
    private $_summary ;
    
    public function __construct(\Model\Game\Skill\Skill $skill, $viewer, $class = "", $summary = true) {
        parent::__construct();
        $this->_skill   = $skill ;
        $this->_viewer  = $viewer ;
        $this->_cs      = ($viewer === null ? null : \Model\Game\Skill\Character::GetFromCharacterAndSkill($viewer, $skill)) ;
        $this->_class   = $class ;
        $this->_summary = $summary ;
    }
    
    public function getContent() {
        
        $res  = "<li class=\"{$this->_class}\"><div class=\"item\">" ;
        $res .= $this->_getIcon() ;
        $res .= $this->_getDescription() ;
        $res .= "</div></li>" ;
        return $res ;
        
    }
    
    private function _getIcon() {
        return "<div class=\"icon\">" . $this->_skill->getImage("icone") . "</div>" ;
    }
    
    private function _getDescription() {
        return "<div class=\"description\">"
                . $this->_getName()
                . $this->_getSummary()
                . "</div>" ;

        
    }
    
    private function _getName() {
        
        if ($this->_viewer === null || $this->_cs === null) {
            $level = ""  ;
        } else {
            $level = " / " . \I18n::LEVEL() . " : " . $this->_cs->level ;
        }
        
        return "<div class=\"name\">" . $this->_skill->getName() . $level . "</div>" ;
    }
    
    private function _getSummaryPrimary() {
        $primary = \Model\Game\Skill\Primary::GetFromSkill($this->_skill) ;
        $res  = "" ;
        $res .= $primary->in->getImage("icone-med") ;
        $res .= " &#8658; " ;
        if ($primary->coef != 1) {
            $res .= $primary->coef . " " ;
        }
        $res .= $primary->out->getImage("icone-med") ;
        return $res ;
    }
    
    private function _getSummarySecondary() {
        $res  = "" ;
        $ins = array() ;
        foreach (\Model\Game\Skill\In::GetFromSkill($this->_skill) as $in) {
            $ins[] = ($in->amount == 1 ? "" : $in->amount) . " " . $in->item->getImage("icone-med") ;
        }
        $outs = array() ;
        foreach (\Model\Game\Skill\Out::GetFromSkill($this->_skill) as $out) {
            $outs[] = ($out->amount == 1 ? "" : $out->amount) . " " . $out->item->getImage("icone-med") ;
        }
        $res .= implode(" + ", $ins) ;
        $res .= " &#8658;  " ;
        $res .= implode(" + ", $outs) ;
        return $res ;
    }
    
    private function _getSummaryField() {
        $field = \Model\Game\Skill\Field::GetFromSkill($this->_skill) ;
        $res  = "" ;
        // $res .= $field->item->getImage("icone-med") ;
        $res .= " &#8658; " ;
        $res .= $field->gain->getImage("icone-med") ;
        return $res ;
    }
    
    private function _getSummary() {
        if (! $this->_summary) {
            return "" ;
        }
        $res = "<div>" ;
        switch ($this->_skill->classname) {
            case "Primary" :
                $res .= $this->_getSummaryPrimary() ; break ;
            case "Secondary" :
                $res .= $this->_getSummarySecondary() ; break ;
            case "FieldGather" :
                $res .= $this->_getSummaryField() ; break ;
            case "Barricade" :
            case "Build" :
            case "Digout" :
            case "DrawMap" :
            case "Fight" :
            case "Heal" :
            case "Learn" :
            case "Move" :
            case "ProspectArcheo" :
            case "ProspectGround" :
            case "ProspectUnderground" :
            case "Research" :
            case "Search" :
            case "StudyBuilding" :
            case "StudyMap" :
            case "Teach" :
            default :
                $res .= \I18n::SPECIAL() . " : " . \I18n::translate("SKILL_CLASSNAME_" . $this->_skill->classname) ;
        }
        $res .= " / " . \I18n::HELP_MSG("/Help/Skill?id={$this->_skill->id}") ;
        
        $res .= "</div>" ;
        return $res ;
    }

}
