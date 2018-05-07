<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Game\Building\Instance;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;

class Library extends Base {

    public function doSpecificStuff($specific, Instance $inst) {
        $library = \Model\Game\Building\Library::GetFromInstance($inst) ;
        $library->name = $specific["name"] ;
        $library->update() ;
    }

    public function getSpecificFieldset() {
        $library = \Model\Game\Building\Library::GetFromInstance($this->id) ;
        
        $fieldset = new FieldSet(\I18n::LIBRARY_PARAMETERS()) ;
        $fieldset->addInput("name", new Text(\I18n::BUILDING_NAME()))
                 ->setValue($library->name);
        return $fieldset ;
        
    }
    
}
