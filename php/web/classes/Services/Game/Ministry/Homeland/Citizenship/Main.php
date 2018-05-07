<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

class Main extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city" ,        new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Homeland") ;
    }
    
    public function getView() {
        return new \Answer\View\Game\Ministry\Homeland\Citizenship($this, $this->city);
    }

}
