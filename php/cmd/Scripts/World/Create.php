<?php

namespace Scripts\World ;

class Create extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("size", new \Quantyl\Form\Fields\Integer("", true)) ;
        $form->addInput("name", new \Quantyl\Form\Fields\Text("", true)) ;
    }
   
    
    public function getView() {
        
        $world = new \Model\Game\World() ;
        $world->size = $this->size ;
        $world->name = $this->name ;
        $world->create() ;
        
        $progress = new \Quantyl\Misc\Counter($world->getCityCount()) ;
        
        $nodeset = new \Quantyl\Misc\Geode\NodeSet($world->getGeode()) ;
        foreach ($nodeset as $node) {
            
            $coord = $node->getString() ;
            
            $city = \Model\Game\City::GetFromCoord($world, $coord) ;
            if ($city === null) {
            
                $vertex = $node->getData() ;

                $city = new \Model\Game\City() ;
                $city->name = "" ;

                $city->world = $world ;
                $city->coord = $node->getString() ;

                $city->setCoordinate($vertex) ;

                $city->create() ;
                
                $progress->step() ;
            }
        }
        
        $progress->elapsed() ;
        
        echo "World Successfully Created with ID = " . $world->id . "\n" ;

        echo $world->id . "\n" ;
        return new \Quantyl\Answer\NullAnswer() ;
    }
    
}
