<?php

namespace Model\Game\Building ;

class Prefecture extends \Quantyl\Dao\BddObject {
    
    // parser
    
    public function create() {
        parent::create();
        
        $prefecture = $this ;
        
        // create links between cities and prefecure
        $done = array($this->instance->city->id => $this->instance->city) ;
        $next = array($this->instance->city) ;
        
        $max = 5 ;
        
        for ($i=0; $i<$max; $i++) {
            $news = array() ;
            foreach ($next as $c) {
                // Link
                \Model\Game\City\Prefecture::createFromValues(array(
                    "city"       => $c,
                    "prefecture" => $prefecture,
                    "distance"   => $i
                )) ;
                $this->dijkstra($c, $news, $done) ;
            }
            $next = $news ;
        }
        
        foreach ($next as $c) {
            \Model\Game\City\Prefecture::createFromValues(array(
                    "city"       => $c,
                    "prefecture" => $prefecture,
                    "distance"   => $max
                )) ;
        }
        
    }
    
    private function dijkstra(\Model\Game\City $city, &$next, &$done) {
        foreach (\Model\Game\City\Neighbour::getFromA($city) as $n) {
            $b = $n->b ;
            if (! isset($done[$b->id])) {
                $next[]  = $n->b ;
                $done[$b->id] = true ;
            }
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return Instance::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromInstance(Instance $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->id)) ;
    }
    
    
}
