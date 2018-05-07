<?php

namespace Form ;

use Model\Game\Building\Instance;
use Model\Game\Building\Map;
use Model\Game\Character;
use Model\Game\Ressource\Inventory;
use Quantyl\Form\Model\Select;

class MapChoser extends Select {
    
    private $_character ;
    
    private $_job ;
    private $_type ;
    private $_level ;
    
    
    public function __construct(Character $c, $label = null, $mandatory = false, $description = null) {
        $this->_character   = $c ;
        $this->_job         = null ;
        $this->_type        = null ;
        $this->_level       = 0 ;
        parent::__construct(Inventory::getBddTable(), $label, $mandatory, $description) ;
    }
    
    public function SetInstance(Instance $i) {
        $this->_job   = $i->job ;
        $this->_type  = $i->type ;
        $this->_level = $i->level ;
        $this->setChoices($this->initChoices()) ;
    }
    
    public function SetJob(\Model\Game\Building\Job $j) {
        $this->_job = $j ;
        $this->setChoices($this->initChoices()) ;
    }
    
    public function initChoices() {
        
        $inv = Inventory::GetMaps($this->_character) ;
        
        $choices = array() ;
        foreach ($inv as $i) {
            $m = Map::getFromItem($i->item) ;
            if (
                    ($this->_job == null  || $this->_job->equals($m->job)   ) &&
                    ($this->_type == null || $this->_type->equals($m->type) ) &&
                    ($this->_level < $m->level)
               ) {
                $choices[$i->id] = $i->item->getName() ;
            }
        }
        return $choices ;
        
    }
    
}