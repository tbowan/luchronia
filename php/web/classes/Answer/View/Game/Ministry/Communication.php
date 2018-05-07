<?php

namespace Answer\View\Game\Ministry ;

class Communication extends Base {

    public function getCityMessages($classes = "") {
        $res = "" ;
        
        foreach ($this->_city->getTownHalls() as $instance) {
            $townhall = \Model\Game\Building\TownHall::GetFromInstance($instance) ;
            $res .= "<h2>" . $townhall->name . "</h2>" ;
            $res .= ($townhall->welcome == "" ? \I18n::NO_MESSAGE() : $townhall->welcome) ;
            if ($this->_isadmin) {
                $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Communication/SetMessage?townhall={$townhall->id}", \I18n::SET_MESSAGE()) ;
            }
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::CITY_MESSAGE(), "", "", $res, $classes) ;
    }
    
    public function getDeliveries($classes = "") {
        $res =  "";
        
        $res .= \I18n::MINISTRY_CITY_HAS_PRESTIGE($this->_city->prestige) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::TARGETED_CITY(),
            \I18n::DELIVERY_SCHEDULED(),
            \I18n::REMAIN(),
            \I18n::ACTIONS()
        )) ;
        foreach (\Model\Game\Ressource\Delivery::GetFromTarget($this->_city) as $delivery) {
            $table->addRow(array(
                $delivery->target->getName(),
                \I18n::_date_time($delivery->scheduled - DT),
                \Model\Game\Ressource\Treasure::CountFromDelivery($delivery),
                new \Quantyl\XML\Html\A("/Game/Ministry/Communication/Delivery?id={$delivery->id}", \I18n::DETAILS())
            )) ;
        }
        $res .= $table ;
        
        if ($this->_isadmin) {
            $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Communication/AskDelivery?city={$this->_city->id}", \I18n::ASK_FOR_DELIVERY()) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::DELIVERIES(), "", "", $res, $classes) ;
    }
    
    public function getSpecific() {
        return ""
                . $this->getCityMessages()
                . $this->getDeliveries() ;
    }

}
