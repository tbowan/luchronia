<?php

namespace Services\Game\Skill\Indoor ;

class Search extends Base {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
    }
    
    public function getToolInput() {
        return new \Form\Tool\Search($this->cs, $this->getComplete()) ;
    }
    
    public function getAmount($munition) {
        return $this->cs->level * parent::getAmount($munition) ;
    }
    
    public function doStuff($amount, $data) {
        
        $msg = "" ;
        for ($i = 0; $i < $amount; $i++) {
            $found = \Model\Game\Ressource\Treasure::GetRandomFromInstance($this->inst) ;
            $msg .= \I18n::FOUND_TREASURE($found->gained, $found->item->getName()) ;
            
            $rest = \Model\Game\Ressource\Inventory::AddItem($this->getCharacter(), $found->item, $found->gained) ;
            
            if ($rest > 0) {
                $me = $this->getCharacter() ;
                $city = $me->position ;
                $item = $found->item ;
                \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
                $msg .= \I18n::INVENTORY_FULL_GIVE_CITY(
                        $rest, $item->getName(),
                        $city->id, $city->id, $city->getName()
                        ) ;
            }
                    
            $found->delGained() ;
            $found->update() ;
            
        }
        
        $msg .= parent::doStuff($amount, $data) ;
        
        return $msg ;
    }
    
}
