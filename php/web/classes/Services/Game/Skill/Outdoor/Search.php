<?php

namespace Services\Game\Skill\Outdoor ;

class Search extends Base {
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->cs->level ;
    }
    
    public function doStuff($amount, $data) {
        
        $msg = "" ;
        for ($i = 0; $i < $amount; $i++) {
            $found = \Model\Game\Ressource\Treasure::GetRandomFromOutside($this->getCity()) ;
            $msg1 = \I18n::FOUND_TREASURE_FULL($found->gained, $found->item->getName()) ;
            $rest = \Model\Game\Ressource\Inventory::AddItem($this->getCharacter(), $found->item, $found->gained) ;
            
            if ($rest > 0) {
                $me = $this->getCharacter() ;
                $city = $me->position ;
                $item = $found->item ;
                \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
                $msg1 = \I18n::FOUND_TREASURE_REMAIN(
                        $found->gained, $item->getName(),
                        $rest, $city->id, $city->getName()
                        ) ;
            }
            
            $msg .= $msg1 ;
            $found->delGained() ;
            $found->update() ;
            
        }
        
        $msg .= parent::doStuff($amount, $data) ;
        
        return $msg ;
    }

    public function getToolInput() {
        return new \Form\Tool\Search($this->cs, $this->getComplete()) ;
    }

}
