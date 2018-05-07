<?php

namespace Services\BackOffice\Ecom\Code ;

class Delete extends \Services\Base\Admin{
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("bonus", new \Quantyl\Form\Model\Id(\Model\Ecom\Code\Bonus::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->bonus->active || \Model\Ecom\Code\Apply::CountTotalUses($this->bonus) > 0) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::DELETE_PROMO_CODE_MSG($this->bonus->code)) ;
    }
    
    public function onProceed($data) {
        $this->bonus->delete() ;
    }
}
