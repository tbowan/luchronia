<?php

namespace Answer\Widget\Game\Building\Market ;

class MySell extends \Quantyl\Answer\Widget {
    
    private $_character ; 
    private $_instance ;
    
    public function __construct(\Model\Game\Building\Instance $instance, \Model\Game\Character $char) {
        parent::__construct();
        $this->_character = $char ;
        $this->_instance  = $instance ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::SELL_RESSOURCE_LIST() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::RESSOURCE(),
            \I18n::TOTAL(),
            \I18n::REMAIN(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Character\Market\Sell::GetFromCharacter($this->_instance, $this->_character) as $sell) {
            $table->addRow(array(
                $sell->ressource->getName(),
                $sell->total,
                $sell->remain,
                $sell->price,
                new \Quantyl\XML\Html\A("/Game/Building/Market/CloseSell?sell={$sell->id}", \I18n::CLOSE_SELL())
            )) ;
        }
        
        if ($table->getRowsCount() > 0) {
            $res .= $table ;
        } else {
            $res .= \I18n::YOU_HAVE_NO_SELL() ;
        }
        
        return $res ;
    }
    
}
