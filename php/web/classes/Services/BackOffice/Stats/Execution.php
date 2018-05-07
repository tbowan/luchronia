<?php

namespace Services\BackOffice\Stats ;

class Execution extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Stats\Script::getBddTable())) ;
    }
    
    public function getView() {
        return new \Widget\BackOffice\Stats\Execution($this->id, "") ;
    }
    
}
