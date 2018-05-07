<?php

namespace Services\User\Quanta ;

class Allopass extends \Services\Base\Connected {
    
    private $_product ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("RECALL", new \Quantyl\Form\Fields\Text()) ;
        $form->addInput("trxid", new \Quantyl\Form\Fields\Text()) ;
        $form->addInput("data", new \Quantyl\Form\Fields\Text()) ;
    }
    
    private function checkProduct() {
        $pr = \Model\Ecom\Allopass\Product::GetFromIdd($this->data) ;
        if ($pr == null) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
        return $pr ;
    }
    
    private function checkCode($code, \Model\Ecom\Allopass\Product $p) {
        // CheckCode
        if ($code == "") {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
        $ids = \Model\Quantyl\Config::ValueFromKey("ALLOPASS_SITE_ID") ;
        $idd = $p->idd ;
        $auth = \Model\Quantyl\Config::ValueFromKey("ALLOPASS_AUTH") ;
        
        $url = "http://payment.allopass.com/api/checkcode.apu?code="
                . urlencode($code)
                . "&auth="
                . urlencode("$ids/$idd/$auth") ;
        
        $answer = file_get_contents($url) ;
        
        if (substr($answer, 0, 2) != "OK") {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
    }
    
    private function checkAlready($trxid) {
        $prev = \Model\Ecom\Allopass\Code::GetFromTransId($trxid) ;
        if ($prev !== null) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        
        // Check Product
        $this->_product = $this->checkProduct() ;
        $this->checkCode($this->RECALL, $this->_product) ;
        $this->checkAlready($this->trxid) ;
        
    }
    
    public function getView() {
        
        // make data
        
        $q = \Model\Ecom\Quanta::createFromValues(array(
            "timestamp" => time(),
            "user" => $this->getUser(),
            "ip" => $this->getRequest()->getServer()->getClientIp(),
            "amount" => $this->_product->amount,
            "type" => "Allopass"
        )) ;

        $c = \Model\Ecom\Allopass\Code::createFromValues(array(
            "quanta" => $q,
            "product" => $this->_product,
            "code" => $this->RECALL,
            "trxid" => $this->trxid
        )) ;
        
        // Credit the user
        
        $u = $this->getUser() ;
        $u->quanta += $this->_product->amount ;
        $u->update() ;
        
        return new \Quantyl\Answer\Message(\I18n::ALLOPASS_DONE($this->_product->amount, $u->quanta)) ;
        
    }
    
}
