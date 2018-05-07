<?php

namespace Answer\Widget\Game\Building\Market ;

class SellDetail extends \Quantyl\Answer\Widget {
    
    private $_ressource ; 
    private $_instance ;
    private $_canpreempt ;
    
    public function __construct(\Model\Game\Building\Instance $instance, \Model\Game\Ressource\Item $ressource, $can_preempt) {
        parent::__construct();
        $this->_ressource = $ressource ;
        $this->_instance  = $instance ;
        $this->_canpreempt = $can_preempt ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::SELL_RESSOURCE_LIST() . "</h2>" ;
        
        $tax = \Model\Game\Tax\Tradable::GetFromInstance($this->_instance) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTER(),
            \I18n::QUANTITY(),
            \I18n::PRICE_TTC(),
            \I18n::ACTIONS()
        )) ;
        
        
        foreach (\Model\Game\Trading\Character\Market\Sell::getOrderForItem($this->_instance, $this->_ressource) as $sell) {
            $act = "" ;
            $act .= new \Quantyl\XML\Html\A("/Game/Building/Market/CompleteSell?sell={$sell->id}", \I18n::BUY()) ;
            if ($this->_canpreempt) {
                $act .= new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Preempt?sell={$sell->id}", \I18n::PREEMPT()) ;
            }
            $table->addRow(array(
                $sell->character->getName(),
                $sell->remain,
                $sell->price * (1.0 + $tax->trans),
                $act
            )) ;
        }
        $res .= $table ;
        
        return $res ;
    }
    
}
