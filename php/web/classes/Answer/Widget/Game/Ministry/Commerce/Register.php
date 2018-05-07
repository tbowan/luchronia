<?php

namespace Answer\Widget\Game\Ministry\Commerce ;

class Register extends \Quantyl\Answer\Widget {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city) {
        parent::__construct();
        $this->_city = $city ;
    }
    
    public function getContent() {
        $res = \I18n::REGISER_MESSAGE() ;
        
        $res .= "<h2>" . \I18n::CITY_REGISTER() . "</h2>" ;
        
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::DATE(),
            \I18n::CHARACTER(),
            \I18n::RESSOURCE(),
            \I18n::DEBIT(),
            \I18n::CREDIT()
        )) ;
        
        foreach (\Model\Game\City\Register::GetIOCity($this->_city) as $log) {
            $row = array() ;
            $row[] = \I18n::_date($log->date - DT) ;
            $row[] = new \Quantyl\XML\Html\A("/Game/Character/Show?id={$log->character->id}", $log->character->getName()) ;
            if ($log->ressource == null) {
                $row[] = \I18n::CREDITS_ICO() . " " . \I18n::CREDITS() ;
            } else {
                $row[] = $log->ressource->getImage("icone-inline") . " " . $log->ressource->getName() ;
            }
            
            if ($log->amount > 0) {
                // Given
                $row[] = number_format($log->amount, 2) ;
                $row[] = "" ;
            } else {
                $row[] = "" ;
                $row[] = number_format(abs($log->amount), 2) ;
            }
            
            $table->addRow($row) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
