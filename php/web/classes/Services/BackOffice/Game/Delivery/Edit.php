<?php

namespace Services\BackOffice\Game\Delivery ;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("item", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Item::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::BACKOFFICE_PRESTIGE_ITEM_MSG(
                $this->item->getName(),
                $this->item->energy,
                number_format($this->item->energy / 100, 2)
                )) ;
        $form->addInput("prestige", new \Quantyl\Form\Fields\Float(\I18n::PRICE()))
             ->setValue($this->item->prestige);
    }
    
    public function onProceed($data) {
        $this->item->prestige = $data["prestige"] ;
        $this->item->update() ;
    }
    
}
