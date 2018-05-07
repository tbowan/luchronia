<?php

namespace Scripts\Cron\Map ;

class Ressource extends Base {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        parent::fillParamForm($form);
        $form->addInput("item", new \Quantyl\Form\Model\Name(\Model\Game\Ressource\Item::getBddTable(), "" , true)) ;
    }
    
    public function getRgbByCity($city) {
        $nat = \Model\Game\Ressource\Natural::GetFromCityAndItem($city, $this->item) ;
        
        $coef = ($nat == null ? 0 : $nat->coef) ;
        $grey = 255 * $coef ;
        
        return array($grey, $grey, $grey) ;
    }

}
