<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\FieldSet;

class Prefecture extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        
        \Model\Game\Building\Prefecture::createFromValues(array(
                "instance"      => $inst,
                "prestige_in"   => $specific["prestige_in"],
                "prestige_out"  => $specific["prestige_out"],
                )) ;
        
        return ;
    }

    public function getSpecificFieldset() {
        
        $fieldset = new FieldSet(\I18n::FIELD_PARAMETERS()) ;
        $fieldset->addInput("prestige_in",  new \Quantyl\Form\Fields\Percentage(\I18n::PRESTIGE_IN())) ;
        $fieldset->addInput("prestige_out", new \Quantyl\Form\Fields\Percentage(\I18n::PRESTIGE_OUT())) ;
        return $fieldset ;
    }
    
}
