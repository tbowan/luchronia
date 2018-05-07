<?php

namespace Services\Game\Skill\Indoor ;

class DrawMap extends Base {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $form->addInput("map", new \Form\DrawMapChooser($this->cs, \I18n::BUILDING_MAP())) ;
    }
    
    public function getAmount($munition) {
        return 1.0 ;
    }
    
    public function doStuff($data, $data) {
        
        $map        = $data["map"] ;
        $type       = $map->type ;
        $research   = \Model\Game\Skill\Research::GetFromCharAndType($this->getCharacter(), $type) ;
        
        if ($research->amount < $map->tech) {
            throw new \Exception(\I18n::EXP_DRAWMAP_NOT_ENOUGH_TECH($map->tech, $research->amount)) ;
        }
        
        $msg = \I18n::DRAWMAP_DONE($map->item->getName()) ;
        
        $rest = \Model\Game\Ressource\Inventory::AddItem($this->getCharacter(), $map->item, 1) ;
        if ($rest > 0) {
            $me = $this->getCharacter() ;
            $city = $me->position ;
            $item = $map->item ;
            \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
            $msg .= \I18n::INVENTORY_FULL_GIVE_CITY(
                    $rest, $item->getName(),
                    $city->id, $city->id, $city->getName()
                    ) ;
        }
        
        $research->amount -= $map->tech ;
        $research->update() ;
        
        
        $msg .= parent::doStuff($data, $data) ;
        return $msg ;
    }

}
