<?php

namespace Form ;

class StudyMapChooser extends \Quantyl\Form\Model\Select  {
    
    private $_type ;
    private $_character ;
    
    public function __construct(\Model\Game\Character $char, \Model\Game\Building\Type $type, $label = null, $mandatory = false, $description = null) {
        $this->_type = $type ;
        $this->_character = $char ;
        parent::__construct(\Model\Game\Ressource\Inventory::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $inv = \Model\Game\Ressource\Inventory::GetMaps($this->_character) ;
        
        $choices = array() ;
        foreach ($inv as $i) {
            $m = \Model\Game\Building\Map::getFromItem($i->item) ;
            if ($this->_type->equals($m->type)) {
                $choices[$i->id] = $i->item->getName() ;
            }
        }
        return $choices ;
        
    }
    
}
