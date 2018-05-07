<?php

namespace Services\Ajax ;

class Item extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Item::getBddTable())) ;
    }
    
    public function getView() {
        $item = $this->id ;
        
        $res = array(
            "id" => $item->id,
            "name" => $item->getName(),
            "img"  => $item->getImgPath(),
            "prestige" => $item->prestige
        ) ;
        
        return new \Quantyl\Answer\Message(json_encode($res)) ;
    }
    
}
