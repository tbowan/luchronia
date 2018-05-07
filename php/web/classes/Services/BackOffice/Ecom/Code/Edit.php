<?php

namespace Services\BackOffice\Ecom\Code ;

class Edit extends \Services\Base\Admin{
    
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
        
        $form->addInput("code",     new \Quantyl\Form\Fields\Text(\I18n::CODE_CODE()))
             ->setValue($this->bonus->code);
        $form->addInput("from",     new \Quantyl\Form\Fields\DateTime(\I18n::CODE_FROM()))
             ->setValue($this->bonus->from);
        $form->addInput("to",       new \Quantyl\Form\Fields\DateTime(\I18n::CODE_TO()))
             ->setValue($this->bonus->to);
        $form->addInput("amount",   new \Quantyl\Form\Fields\Integer(\I18n::CODE_AMOUNT()))
             ->setValue($this->bonus->amount);
        $form->addInput("rate",     new \Quantyl\Form\Fields\Percentage(\I18n::CODE_RATE()))
             ->setValue($this->bonus->rate);
        $form->addInput("max_u",    new \Quantyl\Form\Fields\Integer(\I18n::CODE_MAX_U()))
             ->setValue($this->bonus->max_u);
        $form->addInput("max_t",    new \Quantyl\Form\Fields\Integer(\I18n::CODE_MAX_T()))
             ->setValue($this->bonus->max_t);
    }
    
    public function onProceed($data) {
        $this->bonus->code      = $data["code"] ;
        $this->bonus->from      = $data["from"] ;
        $this->bonus->to        = $data["to"] ;
        $this->bonus->amount    = $data["amount"] ;
        $this->bonus->rate      = $data["rate"] ;
        $this->bonus->max_u     = $data["max_u"] ;
        $this->bonus->max_t     = $data["max_t"] ;
        $this->bonus->update() ;
    }
    
}
