<?php

namespace Services\User\Quanta ;

class Code extends \Services\Base\Character  {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("code", new \Quantyl\Form\Model\Name(\Model\Ecom\Code\Bonus::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->code === null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_QUANTACODE_INVALID()) ;
        } else if (! $this->code->isUsable($this->getUser())) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_QUANTACODE_UNUSABLE()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $msg = "" ;
        // Bonus
        if ($this->code->amount != 0) {
            $msg .= \I18n::QUANTACODE_ABSOLUTE($this->code->amount) ;
        }
        if ($this->code->rate != 0) {
            $msg .= \I18n::QUANTACODE_PERCENTAGE($this->code->amount * 100) ;
        }
        $form->addMessage($msg) ;
        
    }
    
    public function onProceed($data) {
        
        if ($this->code->percentage != 0) {
            throw new \Quantyl\Exception\Http\ServerInternalError("Unimplemented Yet");
        } else {
            
            $user = $this->getUser() ;
            
            $quanta = \Model\Ecom\Quanta::createFromValues(array(
                "timestamp" => time(),
                "user" => $user,
                "ip" => $this->getRequest()->getServer()->getClientIp(),
                "amount" => $this->code->amount,
                "type" => "Code"
            )) ;
            
            $apply = \Model\Ecom\Code\Apply::createFromValues(array(
                "quanta" => $quanta,
                "bonus" => $this->code
            )) ;
            
            $user->quanta += $this->code->amount ;
            $user->update() ;
            
        }
    }
}
