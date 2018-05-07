<?php

namespace Model\Game\Building\Jobs ;

class Prefecture extends Base {
    
    public function onCreated() {
        parent::onCreated();
        
        $prefecture = \Model\Game\Building\Prefecture::GetFromInstance($this->_instance) ;
        
        // create links between cities and prefecure
        $done = array($this->_instance->city) ;
        $next = array($this->_instance->city) ;
        
        $max = 5 ;
        
        for ($i=0; $i<$max; $i++) {
            $news = array() ;
            foreach ($next as $c) {
                // Link
                \Model\Game\City\Prefecture::createFromValues(array(
                    "city" => $c,
                    "prefecture" => $prefecture,
                    "dist" => $i
                )) ;
                $this->dijkstra($c, $news, $done) ;
            }
            $next = $news ;
        }
        
        foreach ($next as $c) {
            \Model\Game\City\Prefecture::createFromValues(array(
                    "city" => $c,
                    "prefecture" => $prefecture,
                    "dist" => $max
                )) ;
        }
        
        
    }
    
    public function dijkstra(\Model\Game\City $city, &$next, &$done) {
        
        foreach (\Model\Game\City\Neighbour::getFromA($city) as $n) {
            $n = $n->b ;
            if (! isset($done[$b->id])) {
                $next[]  = $n->b ;
                $done[$b->id] = true ;
            }
        }
    }
    
    
}
