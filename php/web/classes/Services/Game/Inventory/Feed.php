<?php

namespace Services\Game\Inventory ;

class Feed extends \Services\Base\Character {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $char = $this->getCharacter() ;
        $form->addInput("items", new \Form\Feed($char)) ;
        
    }
    
    public function onProceed($data) {
        
        $char = $this->getCharacter() ;
        
        $energy = 0 ;
        $hydration = 0 ;
        
        foreach ($data["items"] as $tab) {
            $inv = $tab[0] ;
            $qtty = $tab[1] ;
            
            $eatable    = \Model\Game\Ressource\Eatable::GetByItem($inv->item) ;
            $drinkable  = \Model\Game\Ressource\Drinkable::GetByItem($inv->item) ;

            if ($eatable != null && $eatable->canEat($char)) {
                $energy += $eatable->energy * $qtty ;
            } else if ($drinkable != null) {
                $hydration += $drinkable->hydration * $qtty ;
                if ($char->race->equals(\Model\Enums\Race::HUMAN())) {
                    $energy += $drinkable->energy * $qtty ;
                }
            }

            
            $inv->amount -= $qtty ;
            $inv->update() ;
        }
        
        $char->feed($energy, $hydration) ;

    }
    
}
