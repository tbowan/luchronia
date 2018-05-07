<?php


namespace Scripts\World ;

use Model\Game\City\Neighbour as MNeighbour ;
use Model\Game\World;
use Quantyl\Answer\NullAnswer;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Misc\Geode\Geode;
use Quantyl\Request\Request;
use Quantyl\Service\EnhancedService;

class Neighbour extends EnhancedService {
    
    private $_cnt ;
    
    public function fillParamForm(Form &$form) {
        $form->addInput("world", new Id(World::getBddTable())) ;
    }
    
    public function setVertex() {
        foreach (Geode::$_vertexes as $table) {
            $a = $this->world->getCity($table[0]) ;
            
            for ($i=1; $i<6; $i++) {
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($table[0], $table[$i], null, 1, 0),
                    "order" => $i - 1
                    )) ;
            }
            
            $this->step() ;
        }
    }
    
    public function setEdges() {
        
        foreach (Geode::$_edges as $edge) {
            
            
            for ($i=1; $i<$this->world->size; $i++) {
                
                $a = $this->world->getCity($edge[0], $edge[1], null, $i, 0) ;
                
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($edge[0], $edge[1], null, $i + 1, 0),
                    "order" => 0
                    )) ;
                
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($edge[0], $edge[1], $edge[2], $i, 1),
                    "order" => 1
                    )) ;
                
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($edge[0], $edge[1], $edge[2], $i-1, 1),
                    "order" => 2
                    )) ;
                
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($edge[0], $edge[1], null, $i-1, 0),
                    "order" => 3
                    )) ;
                
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($edge[0], $edge[1], $edge[3], $i-1, 1),
                    "order" => 4
                    )) ;
                
                MNeighbour::createFromValues(array(
                    "a" => $a,
                    "b" => $this->world->getCity($edge[0], $edge[1], $edge[3], $i, 1),
                    "order" => 5
                    )) ;
                
                
                $this->step() ;
            }

        }
        
    }
    
    public function setFaces() {
        
        foreach (Geode::$_faces as $face) {
            $a = $face[0] ;
            $b = $face[1] ;
            $c = $face[2] ;
            
            for ($i = 1; $i < $this->world->size; $i++) {
                for ($j = 1; $j < $this->world->size - $i; $j++) {
                    
                    $me = $this->world->getCity($a, $b, $c, $i, $j) ;
                    
                    MNeighbour::createFromValues(array(
                        "a" => $me,
                        "b" => $this->world->getCity($a, $b, $c, $i+1, $j),
                        "order" => 0
                    )) ;
                    MNeighbour::createFromValues(array(
                        "a" => $me,
                        "b" => $this->world->getCity($a, $b, $c, $i+1, $j-1),
                        "order" => 1
                    )) ;
                    MNeighbour::createFromValues(array(
                        "a" => $me,
                        "b" => $this->world->getCity($a, $b, $c, $i, $j-1),
                        "order" => 2
                    )) ;
                    MNeighbour::createFromValues(array(
                        "a" => $me,
                        "b" => $this->world->getCity($a, $b, $c, $i-1, $j),
                        "order" => 3
                    )) ;
                    MNeighbour::createFromValues(array(
                        "a" => $me,
                        "b" => $this->world->getCity($a, $b, $c, $i-1, $j+1),
                        "order" => 4
                    )) ;
                    MNeighbour::createFromValues(array(
                        "a" => $me,
                        "b" => $this->world->getCity($a, $b, $c, $i, $j+1),
                        "order" => 5
                    )) ;
                    
                    $this->step() ;
                }
            }
        }
    }
    
    public function step() {
        $this->_cnt->step() ;
    }
    
    public function getView() {
        
        $this->_cnt = new \Quantyl\Misc\Counter($this->world->getCityCount()) ;
        
        $this->setVertex() ;
        $this->setEdges() ;
        $this->setFaces() ;
        
        $this->_cnt->step() ;
        $this->_cnt->elapsed() ;
    }
    
    
}
