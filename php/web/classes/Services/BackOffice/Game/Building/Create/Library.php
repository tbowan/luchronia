<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;

class Library extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        
        \Model\Game\Building\Library::createFromValues(array(
                "instance" => $inst,
                "name"     => $specific["name"],
                )) ;
        
        return ;
    }

    public function getSpecificFieldset() {
        
        $fieldset = new FieldSet(\I18n::LIBRARY_PARAMETERS()) ;
        $fieldset->addInput("name", new Text(\I18n::BUILDING_NAME())) ;
        return $fieldset ;
    }
    
}
