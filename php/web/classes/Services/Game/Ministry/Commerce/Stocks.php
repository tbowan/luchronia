<?php

namespace Services\Game\Ministry\Commerce ;

class Stocks extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function getView() {
        return new \Widget\Game\Ministry\Stocks($this->city);
    }
    

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }

}
