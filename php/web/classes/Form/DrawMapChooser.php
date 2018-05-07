<?php

namespace Form ;



class DrawMapChooser extends \Quantyl\Form\Model\Select {
    
    private $_cs ;
    
    public function __construct(\Model\Game\Skill\Character $c, $label = null, $mandatory = false, $description = null) {
        $this->_cs   = $c ;
        parent::__construct(\Model\Game\Building\Map::getBddTable(), $label, $mandatory, $description) ;
    }
    
    public function initChoices() {
        
        $choices = array() ;
        foreach (\Model\Game\Building\Map::getFromSkill($this->_cs->skill) as $map) {
            if ($map->level <= $this->_cs->level) {
                $choices[$map->id] = $map->item->getName() ;
            }
        }
        return $choices ;
        
    }
    
}