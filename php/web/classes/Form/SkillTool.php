<?php

namespace Form ;

class SkillTool extends \Quantyl\Form\Model\Select {
    
    private $_skill ;
    private $_character ;
    private $_coef ;
    
    public function __construct(\Model\Game\Skill\Skill $s, \Model\Game\Character $c, $coef = 1.0) {
        $this->_skill       = $s ;
        $this->_character   = $c ;
        $this->_coef        = $coef ;
        parent::__construct(\Model\Game\Ressource\Inventory::getBddTable(), \I18n::TOOL_CHOICE()) ;
    }
    
    public function initChoices() {
        $s = $this->_skill ;
        $c = $this->_character ;
        
        $choices = array() ;
        if ($s->by_hand != null) {
            $choices[0] = \I18n::BY_HAND() . " (" . ($s->by_hand * $this->_coef) . ")" ;
        }
        
        $items = \Model\Game\Ressource\Inventory::GetEquipement($c) ;
        foreach ($items as $e) {
            $t = \Model\Game\Skill\Tool::GetFromSkillAndItem($s, $e->item) ;
            if ($e->amount >= 0.01 && $t != null) {
                $choices[$e->id] = $e->item->getName() . " (" . ($t->coef * $this->_coef) . ")" ;
            }
        }
        return $choices ;
    }

}
