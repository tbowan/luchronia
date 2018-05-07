<?php

namespace Services\Game\Ministry\Communication ;

class AskDelivery extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::MINISTRY_CITY_HAS_PRESTIGE($this->city->prestige)) ;
        $form->addInput("cmd", new \Form\Delivery($this->city->prestige, \I18n::DELIVERY())) ;
    }
    
    public function onProceed($data) {
        // 1. check cost
        $cost = 0.0 ;
        foreach ($data["cmd"]["items"] as $cpl) {
            $cost += $cpl["item"]->prestige * $cpl["qtty"] ;
        }
        $cost *= 1.0 + $data["cmd"]["precision"] ;
        
        if ($this->city->prestige < $cost) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_PRESTIGE($cost, $this->city->prestige)) ;
        }

        $this->city->prestige -= $cost ;
        $this->city->update() ;
        
        // Proceed
        $delivery = $this->makeDelivery($data) ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Ministry/Communication/Delivery?id={$delivery->id}")) ;
    }
    
    public function makeDelivery($data) {
        
        $target    = $this->city ;
        $precision = $data["cmd"]["precision"] ;
        $inverse   = 1.0 - $precision ;
        
        $this->_initNeighbours($target) ;
        
        $delivery = \Model\Game\Ressource\Delivery::createFromValues(array(
            "target" => $target,
            "scheduled" => time(),
            "backoffice" => true
        )) ;
        
        foreach ($data["cmd"]["items"] as $couple) {
            $item = $couple["item"] ;
            $qtty = $couple["qtty"] ;
            if ($qtty * $inverse > 0) {
                $this->makeTreasure($item, $qtty * $inverse, $delivery) ;
            }
            if ($qtty * $precision> 0) {
                $this->makeStocks($item, $qtty * $precision, $target) ;
            }
        }
        return $delivery ;
    }
    
    private $_n ;
    
    public function _initNeighbours($center) {
        $this->_n = array() ;
        $this->_n[] = $center ;
        
        foreach (\Model\Game\City\Neighbour::getFromA($center) as $n) {
            $this->_n[] = $n->b ;
        }
    }
    
    public function getRandomPos() {
        $idx = rand(0, count($this->_n) - 1) ;
        return $this->_n[$idx] ;
    }
    
    public function makeTreasure($item, $qtty, $delivery) {
        $gained = min(100, max(1, floor(1000 / $item->energy))) ;
        
        \Model\Game\Ressource\Treasure::createFromValues(array(
            "job" => null,
            "type" => null,
            "biome" => null,
            "city" => $this->getRandomPos(),
            "item" => $item,
            "amount" => $qtty,
            "infinite" => false,
            "gained" => min($gained, $qtty),
            "delivery" => $delivery
        )) ;
    }
    
    public function makeStocks($item, $qtty, $target) {
        $remain = \Model\Game\Ressource\City::DonateExists($target, $item, $qtty) ;
        \Model\Game\Ressource\City::DonateNew($target, $item, $remain) ;
    }
    
    

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Communication") ;
    }
}
