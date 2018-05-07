<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Game\Building\Field as MField;
use Model\Game\Building\Instance;
use Quantyl\Form\FieldSet;

class Prefecture extends Base {

    public function doSpecificStuff($specific, Instance $inst) {
        $prefecture = \Model\Game\Building\Prefecture::GetFromInstance($inst) ;
        $prefecture->prestige_in  = $specific["prestige_in"] ;
        $prefecture->prestige_out = $specific["prestige_out"] ;
        $prefecture->update() ;
    }

    public function getSpecificFieldset() {
        
        $prefecture = \Model\Game\Building\Prefecture::GetFromInstance($this->id) ;
        
        $fieldset = new FieldSet(\I18n::FIELD_PARAMETERS()) ;
        $fieldset->addInput("prestige_in",  new \Quantyl\Form\Fields\Percentage(\I18n::PRESTIGE_IN()))
                 ->setValue($prefecture->prestige_in);
        $fieldset->addInput("prestige_out", new \Quantyl\Form\Fields\Percentage(\I18n::PRESTIGE_OUT()))
                 ->setValue($prefecture->prestige_out);
        return $fieldset ;
    }
    
}
