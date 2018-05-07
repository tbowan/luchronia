<?php

namespace Services\BackOffice\Game\Delivery ;

class Create extends \Services\Base\Admin {
    
    private $_n ;
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), \I18n::CITY_ID())) ;
        $form->addInput("delivery", new \Form\Delivery(0, \I18n::DELIVERY())) ;
    }
    
    public function onProceed($data) {
        
        $target    = $data["city"] ;
        $precision = $data["delivery"]["precision"] ;
        $inverse   = 1.0 - $precision ;
        
        $this->_initNeighbours($target) ;
        
        $delivery = \Model\Game\Ressource\Delivery::createFromValues(array(
            "target" => $target,
            "scheduled" => time(),
            "backoffice" => true
        )) ;
        
        foreach ($data["delivery"]["items"] as $couple) {
            $item = $couple["item"] ;
            $qtty = $couple["qtty"] ;
            $this->makeTreasure($item, $qtty * $inverse, $delivery) ;
            $this->makeStocks($item, $qtty * $precision, $target) ;
        }
        
    }
    
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
    
}
