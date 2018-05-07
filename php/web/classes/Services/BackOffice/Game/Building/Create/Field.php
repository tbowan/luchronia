<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\Fields\Float;
use Quantyl\Form\FieldSet;

class Field extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        
        \Model\Game\Building\Field::createFromValues(array(
                "instance" => $inst,
                "item"     => $specific["item"],
                "amount"   => $specific["amount"]
                )) ;
        
        return ;
    }

    public function getSpecificFieldset() {
        
        $fieldset = new FieldSet(\I18n::FIELD_PARAMETERS()) ;
        $fieldset->addInput("item", new \Form\NaturalItem($this->city, \I18n::RESSOURCE())) ;
        $fieldset->addInput("amount", new Float(\I18n::AMOUNT())) ;
        return $fieldset ;
    }
    
}
