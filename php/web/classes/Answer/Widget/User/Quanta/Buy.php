<?php

namespace Answer\Widget\User\Quanta ;

class Buy extends \Quantyl\Answer\Widget {
    
    public function getCode() {
        $res = "<h2>" . \I18n::BUY_WITH_CODE() . "</h2>" ;
        $res .= \I18n::BUY_WITH_CODE_MESSAGE() ;
        
        $form = new \Quantyl\Form\Form() ;
        $form->setAction("/User/Quanta/Code", "get") ;
        $form->addInput("code", new \Quantyl\Form\Fields\Text(\I18n::CODE())) ;
        $form->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
        $res .= $form->getContent() ;
        
        return $res ;
        
    }
    
    public function getAllopassButton(\Model\Ecom\Allopass\Product $p) {
        $ids = \Model\Quantyl\Config::ValueFromKey("ALLOPASS_SITE_ID") ;
        $idd = $p->idd ;
        return "" . new \Quantyl\XML\Html\A("https://payment.allopass.com/buy/buy.apu?ids=$ids&idd=$idd&data=$idd", \I18n::BUY()) ;
    }
    
    public function getAllopass() {
        $res = "<h2>" . \I18n::BUY_WITH_ALLOPASS() . "</h2>" ;
        $res .= \I18n::BUY_WITH_ALLOPASS_MESSAGE() ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::NAME(),
            \I18n::AMOUNT(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Ecom\Allopass\Product::GetAll() as $p) {
            $table->addRow(array(
                $p->name,
                $p->amount,
                $this->getAllopassButton($p)
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
    public function getContent() {
        $res = "" ;
        $res .= \I18n::QUANTA_BUY_MESSAGE() ;
        $res .= $this->getCode() ;
        $res .= $this->getAllopass() ;
        return $res ;
    }
    
}
