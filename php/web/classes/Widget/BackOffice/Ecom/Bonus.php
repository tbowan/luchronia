<?php

namespace Widget\BackOffice\Ecom ;

class Bonus extends \Quantyl\Answer\Widget {
    
    
    public function getCodes() {
        $res = "<h2>" . \I18n::PROMOTIONNAL_CODES() . "</h2>" ;
        
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Ecom/Code/Add", \I18n::ADD()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CODE(),
            \I18n::DATES(),
            \I18n::BONUS(),
            \I18n::MAX(),
            \I18n::ACTIVE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Ecom\Code\Bonus::GetAll() as $code) {
            $table->addRow(array(
                $code->code,
                \I18n::_date_time($code->from) . "<br/>" . \I18n::_date_time($code->to),
                $code->amount . "<br/>" . $code->rate . " %",
                $code->max_u . "<br/>" . $code->max_t,
                $code->active,
                new \Quantyl\XML\Html\A("/BackOffice/Ecom/Code/Activation?bonus={$code->id}", \I18n::ACTIVATION())
                . "<br/>"
                . new \Quantyl\XML\Html\A("/BackOffice/Ecom/Code/Edit?bonus={$code->id}", \I18n::EDIT())
                . new \Quantyl\XML\Html\A("/BackOffice/Ecom/Code/Delete?bonus={$code->id}", \I18n::DELETE())
            )) ;
        }
        $res .= $table ;
        
        
        return $res ;
        
    }
    
    public function getContent() {
        return ""
                . $this->getCodes() ;
    }
    
}
