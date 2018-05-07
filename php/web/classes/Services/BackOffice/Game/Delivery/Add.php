<?php

namespace Services\BackOffice\Game\Delivery ;

class Add extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("item", new \Quantyl\Form\Model\Select(\Model\Game\Ressource\Item::getBddTable(), \I18n::ITEM(), true)) ;
    }
    
    public function onProceed($data) {
        $id = $data["item"]->id ;
        $this->setAnswer(new \Quantyl\Answer\Redirect("/BackOffice/Game/Delivery/Edit?item={$id}")) ;
    }
    
}
