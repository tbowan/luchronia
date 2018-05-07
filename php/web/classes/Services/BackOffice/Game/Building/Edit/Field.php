<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Form\NaturalItem;
use Model\Game\Building\Field as MField;
use Model\Game\Building\Instance;
use Quantyl\Form\Fields\Float;
use Quantyl\Form\FieldSet;

class Field extends Base {

    public function doSpecificStuff($specific, Instance $inst) {
        $field = MField::GetFromInstance($inst) ;
        $field->item = $specific["item"] ;
        $field->amount = $specific["amount"] ;
        $field->update() ;
    }

    public function getSpecificFieldset() {
        $field = MField::GetFromInstance($this->id) ;
        
        $fieldset = new FieldSet(\I18n::FIELD_PARAMETERS()) ;
        $fieldset->addInput("item", new NaturalItem($this->id->city, \I18n::RESSOURCE()))
                 ->setValue($field->item);
        $fieldset->addInput("amount", new Float(\I18n::AMOUNT()))
                 ->setValue($field->amount);
        return $fieldset ;
    }
    
}
