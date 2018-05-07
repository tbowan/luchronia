<?php

namespace Services\Game\Ministry\Development\Create ;

use Form\NaturalItem;
use Model\Game\Building\Field as MField;
use Model\Game\Building\Instance;
use Quantyl\Form\FieldSet;

class Field extends Base {
    
    public function doSpeficicStuff(Instance $i, $data) {
        MField::createFromValues(array(
                "instance" => $i,
                "item"     => $data["item"],
                "amount"   => 1.0
                )) ;
        
        return ;
    }

    public function getSpecificFieldSet() {
        $fieldset = new FieldSet(\I18n::FIELD_PARAMETERS()) ;
        $fieldset->addInput("item", new \Form\FieldItem($this->city, \I18n::RESSOURCE())) ;
        return $fieldset ;
    }

    public function getSpecificMessage() {
        
    }

}
