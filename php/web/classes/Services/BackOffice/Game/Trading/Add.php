<?php

namespace Services\BackOffice\Game\Trading ;

class Add extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("type",     new \Quantyl\Form\Model\EnumSimple(\Model\Game\Trading\Type::getBddTable(), \I18n::TYPE())) ;
        $form->addInput("item",     new \Form\ItemSelect(\I18n::ITEM(), true)) ;
        $form->addInput("amount",   new \Quantyl\Form\Fields\Float(\I18n::AMOUNT())) ;
        $form->addInput("infinite", new \Quantyl\Form\Fields\Boolean(\I18n::INFINITE_ORDER())) ;
        $form->addInput("price",    new \Quantyl\Form\Fields\Float(\I18n::PRICE(), true)) ;
    }
    
    public function onProceed($data) {
        \Model\Game\Trading\Nation::createFromValues(array(
            "nation" => null,
            "item" => $data["item"],
            "price" => $data["price"],
            "amount" => ($data["infinite"] ? null : $data["amount"]),
            "type" => $data["type"]
        )) ;
    }
    
}
