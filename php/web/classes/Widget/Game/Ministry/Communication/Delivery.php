<?php

namespace Widget\Game\Ministry\Communication ;

class Delivery extends \Quantyl\Answer\Widget {
    
    private $_delivery ;
    
    public function __construct(\Model\Game\Ressource\Delivery $delivery) {
        parent::__construct();
        $this->_delivery = $delivery ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::DELIVERY() . "</h2>" ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::TARGETED_CITY() . " : </strong>" . $this->_delivery->target->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::DELIVERY_SCHEDULED() . " : </strong>" . \I18n::_date_time($this->_delivery->scheduled - DT) . "</li>" ;
        $res .= "</ul>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::POSITION(),
            \I18n::RESSOURCE(),
            \I18n::REMAIN(),
            \I18n::GAINED()
        )) ;
        
        foreach (\Model\Game\Ressource\Treasure::GetFromDelivery($this->_delivery) as $tr) {
            $table->addRow(array(
                $tr->city->getName(),
                $tr->item->getImage("icone") . " " . $tr->item->getName(),
                $tr->amount,
                $tr->gained
            )) ;
        }
        $res .= $table ;
        
        return $res ;
    }
    
}
