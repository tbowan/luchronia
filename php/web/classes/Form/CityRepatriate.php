<?php

namespace Form ;

class CityRepatriate implements \Quantyl\Form\Input  {
    
    private $_cities ;
    private $_city ;
    private $_character ;
    private $_mandatory ;
    
    public function __construct(\Model\Game\Character $char, $mandatory = true) {
        $this->_character = $char ;
        $this->_city = null ;
        $this->_mandatory = $mandatory ;
        $this->_cities = array() ;
        
        foreach (\Model\Game\City::GetRepatriate($char) as $city) {
            $this->_cities[$city->id] = $city ;
        }
        foreach (\Model\Game\Citizenship::GetFromCitizen($this->_character) as $citizenship) {
            $city = $citizenship->city ;
            $this->_cities[$city->id] = $city ;
        }

    }
    
    public function getValue() {
        return $this->_city ;
    }

    public function isValid() {
        return $this->_city !== null || ! $this->_mandatory ;
    }

    public function parseValue($value) {
        if ($value != null) {
            if (isset($this->_cities[$value])) {
                $this->_city = $this->_cities[$value] ;
            } else {
                $this->_city = null ;
            }
        } else {
            $this->_city = null ;
        }
    }

    public function setValue($value) {
        if ($value != null && $this->_cities[$value->getId()]) {
            $this->_city = $value ;
        }
    }

    private function getBuildings($c) {
        $res = "" ;
        foreach (\Model\Game\Building\Instance::GetFromCity($c) as $i) {
            $res .= $i->getImage("icone") ;
        }
        return $res ;
    }
    
    public function getHTML($key = null) {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CITY(),
            \I18n::BUILDINGS(),
            \I18n::POPULATION(),
            \I18n::DISTANCE(),
            \I18n::TIME_COST(),
            \I18n::CREDIT_COST(),
            ""
        )) ;
        
        $kmc = (2.0 * pi() * 1736.0) / (5 * $this->_character->position->world->size) ;
        
        foreach ($this->_cities as $id => $c) {
            $dist_km = \Model\Game\City::GetDist($this->_character->position, $c) ;
            $dist_city = $dist_km / $kmc ;
            $time_cost = round(10000 * $dist_city, 2) ;
            $credit_cost = round($c->repatriate_cost * $dist_km, 2);
            
            $table->addRow(array(
                "<input id=\"$key-$id\" type=\"radio\" name=\"$key\" value=\"$id\"/> ".
                new \Quantyl\XML\Html\A("/Game/City?id={$c->id}", $c->getName()),
                $this->getBuildings($c),
                \Model\Game\Character::CountPopulation($c) . " / " . \Model\Game\Character::CountCitizen($c),
                $dist_km . " Km",
                $time_cost,
                $credit_cost
            )) ;
            
        }
        
        return "" . $table ;
    }

}
