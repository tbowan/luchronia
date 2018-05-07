<?php

namespace Answer\Widget\Game\Building ;

class SkillList extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $inst, \Model\Game\Character $me, $classes = "") {
        
        parent::__construct(
                \I18n::SKILLS(),
                "",
                "",
                $this->makeList($inst, $me),
                $classes) ;
    }
    
    
    public function makeList(\Model\Game\Building\Instance $inst, \Model\Game\Character $me) {
        
        $res = "<ul class=\"itemList\">" ;
        
        foreach (\Model\Game\Skill\Skill::GetFromInstance($inst) as $skill) {
            $cs   = \Model\Game\Skill\Character::GetFromCharacterAndSkill($me, $skill) ;
            
            if ($cs != null && $cs->level > 0) {
                $cost = \Model\Game\Tax\Complete::Factory($me, $skill, $inst->job, $inst->city) ;

                $res .= "<li><div class=\"item\">" ;
                $res .= "<div class=\"icon\">" . $skill->getImage() . "</div>" ;
                $res .= "<div class=\"description\">"
                        . $this->getName($cs, $skill)
                        . $this->getTime($cs)
                        . $this->getTax($cost)
                        . $this->getLink($cs, $skill, $me, $inst)
                        . "</div>" ;

                $res .= "</div></li>" ;
            }
        }
        
        $res .= "</ul>" ;
        return $res ;
    }
    
    private function getName($cs, \Model\Game\Skill\Skill $skill) {
        $res = "<div class=\"name\">" . $skill->getName() ;
        if ($cs != null) {
            $res .= " (" . \I18n::LEVEL() . " : " . $cs->level . ")" ;
        }
        $res .= "</div>" ;
        return $res ;
    }
    
    private function getTime($cs) {
        $res = "<div class=\"time\">" ;
        $res .= \I18n::TIME_COST() . " : ";
        if ($cs != null) {
            $res .= $cs->getTimeCost()  ;
        } else {
            $res .= "-" ;
        }
        $res .= "</div>" ;
        return $res ;
    }
    
    private function getTax(\Model\Game\Tax\Complete $cost) {
        $res = "<div class=\"tax\">" ;
        $res .= \I18n::TAX() . " : ";
        $res .= $cost->getVar() . " . x + " . $cost->getFix() ;
        $res .= "</div>" ;
        return $res ;
    }
    
    private function getLink($cs, \Model\Game\Skill\Skill $skill, \Model\Game\Character $me, \Model\Game\Building\Instance $inst) {
        $res = "<div class=\"links\">" ;
        if ($cs != null && $cs->level > 0) {
            $res .= new \Quantyl\XML\Html\A("/Game/Skill/Indoor/?cs={$cs->id}&inst={$inst->id}", \I18n::EXECUTE_SKILL()) ;
        } else if ($skill->cost <= $me->point) {
            $res .= new \Quantyl\XML\Html\A("/Game/LevelUp/NewSkill?skill={$skill->id}", \I18n::BUY()) ;
        }
        $res .= "</div>" ;
        return $res ;
    }
    
}
