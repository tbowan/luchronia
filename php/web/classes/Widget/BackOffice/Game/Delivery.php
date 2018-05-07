<?php

namespace Widget\BackOffice\Game ;

class Delivery extends \Quantyl\Answer\Widget {
    
    public function getPrestigePrice() {
        $res = "<h2>" . \I18n::PRESTIGE_PRICES() . "</h2>" ;
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Game/Delivery/Add", \I18n::ADD()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::RESSOURCE(),
            \I18n::PRESTIGE_PER_ENERGY(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        foreach (\Model\Game\Ressource\Item::GetDeliverable() as $item) {
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                number_format($item->prestige / $item->energy, 2),
                $item->prestige,
                new \Quantyl\XML\Html\A("/BackOffice/Game/Delivery/Edit?item={$item->id}", \I18n::EDIT())
            )) ;
        }
        $res .= $table ;
        return $res ;
    }
    
    public function getDeliveries() {
        $res = "<h2>" . \I18n::DELIVERIES() . "</h2>" ;
        
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Game/Delivery/Create", \I18n::CREATE_DELIVERY()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::DELIVERY_SCHEDULED(),
            \I18n::TARGETED_CITY(),
            \I18n::REMAIN(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\Delivery::GetFromBackOffice() as $delivery) {
            $table->addRow(array(
                \I18n::_date_time($delivery->scheduled - DT),
                $delivery->target->getName(),
                \Model\Game\Ressource\Treasure::CountFromDelivery($delivery),
                new \Quantyl\XML\Html\A("/Game/Ministry/Communication/Delivery?id={$delivery->id}", \I18n::DETAILS()) .
                new \Quantyl\XML\Html\A("/BackOffice/Game/Delivery/Delete?id={$delivery->id}", \I18n::DELETE())
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
    public function getContent() {
        return ""
                . $this->getPrestigePrice()
                . $this->getDeliveries() ;
    }
    
}
