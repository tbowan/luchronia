<?php

namespace Services\BackOffice\Game\Item ;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("item", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Item::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::BACKOFFICE_PRICE_ITEM_MSG(
                $this->item->getName(),
                $this->item->energy,
                ceil($this->item->energy / 7.2)
                )) ;
        $form->addInput("price", new \Quantyl\Form\Fields\Integer(\I18n::PRICE()))
             ->setValue($this->item->price);
    }
    
    public function onProceed($data) {
        $this->item->price = $data["price"] ;
        $this->item->update() ;
    }
    
}
