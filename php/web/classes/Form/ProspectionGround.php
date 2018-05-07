<?php

namespace Form ;

class ProspectionGround extends \Quantyl\Form\Model\Select{
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $char, $label = null, $mandatory = false, $description = null) {
        $this->_char = $char ;
        parent::__construct(\Model\Game\Ressource\Item::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $biome = $this->_char->position->biome ;
        $choices = array() ;
        foreach (\Model\Game\Ressource\Ecosystem::GetFromBiome($biome) as $ecosystem) {
            $item = $ecosystem->item ;
            if ($biome->equals($ecosystem->biome) && ! \Model\Game\Ressource\Prospection::HasBeenProspected($this->_char, $this->_char->position, $item)) {
                $choices[$item->id] = $item->getName() ;
            }
        }
        return $choices ;
    }
    
}
