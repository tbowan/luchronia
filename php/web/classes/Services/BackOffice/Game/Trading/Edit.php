<?php

namespace Services\BackOffice\Game\Trading ;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("order", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Nation::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("type", new \Quantyl\Form\Model\EnumSimple(\Model\Game\Trading\Type::getBddTable(), \I18n::TYPE()))
             ->setValue($this->order->type);
        $form->addInput("item", new \Form\ItemSelect(\I18n::ITEM()))
             ->setValue($this->order->item);
        $form->addInput("amount", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT()))
             ->setValue($this->order->amount);
        $form->addInput("infinite", new \Quantyl\Form\Fields\Boolean(\I18n::INFINITE_ORDER()))
             ->setValue($this->order->amount === null);
        $form->addInput("price", new \Quantyl\Form\Fields\Float(\I18n::PRICE()))
             ->setValue($this->order->price);
    }
    
    public function onProceed($data) {
        $order = $this->order ;
        $order->type = $data["type"] ;
        $order->item = $data["item"] ;
        $order->amount = ($data["infinite"] ? null : $data["amount"]) ;
        $order->price = $data["price"] ;
        $order->update() ;
    }
    
}
