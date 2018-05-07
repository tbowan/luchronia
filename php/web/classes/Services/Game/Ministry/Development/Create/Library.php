<?php

namespace Services\Game\Ministry\Development\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;

class Library extends Base {

    public function getSpecificFieldset() {
        $fieldset = new FieldSet(\I18n::LIBRARY_PARAMETERS()) ;
        $fieldset->addInput("name", new Text(\I18n::BUILDING_NAME())) ;
        return $fieldset ;
    }

    public function getSpecificMessage() {
        
    }

    public function doSpeficicStuff(Instance $i, $data) {
        \Model\Game\Building\Library::createFromValues(array(
                "instance" => $i,
                "name"     => $data["name"],
                )) ;
        return ;
    }

}
