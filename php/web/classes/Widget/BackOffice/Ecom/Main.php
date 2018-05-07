<?php

namespace Widget\BackOffice\Ecom ;

class Main extends \Quantyl\Answer\Widget {
    
    
    public function getAllopass() {
        return new \Answer\Widget\Misc\Card1(
                \I18n::ALLOPASS(),
                \I18n::BAKOFFICE_ECOM_ALLOPASS_MSG(\Model\Ecom\Allopass\Code::TotalSince(0))
                . new \Quantyl\XML\Html\A("/BackOffice/Ecom/Allopass/", \I18n::KNOW_MORE())
                ) ;
        
    }
    
    public function getCode() {
        return new \Answer\Widget\Misc\Card1(
                \I18n::ECOM_CODE(),
                \I18n::BAKOFFICE_ECOM_CODE_MSG()
                . new \Quantyl\XML\Html\A("/BackOffice/Ecom/Code/", \I18n::KNOW_MORE())
                ) ;
    }
    
    public function getContent() {
        return ""
                . $this->getCode()
                . $this->getAllopass() ;
    }
    
}
