<?php

namespace Services\Game\Ministry\Homeland ;

use Model\Enums\Door as EDoor;
use Model\Game\Building\Wall;
use Model\Game\Politic\Ministry;
use Quantyl\Form\Form;
use Quantyl\Form\Model\EnumSimple;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Door extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->wall->instance->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Homeland") ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("wall", new Id(Wall::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("door", new EnumSimple(EDoor::getBddTable(), \I18n::DOOR_WALL()))
             ->setValue($this->wall->door);
    }
    
    public function onProceed($data, Request $req) {
        $this->wall->door = $data["door"] ;
        $this->wall->update() ;
    }



}
