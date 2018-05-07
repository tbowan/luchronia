<?php

namespace Services\BackOffice\Game\Delivery ;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Delivery::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::DELETE_DELIVERY_MSG(
                \I18n::_date_time($this->id->scheduled - DT),
                \Model\Game\Ressource\Treasure::CountFromDelivery($this->id)
                )) ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
}
