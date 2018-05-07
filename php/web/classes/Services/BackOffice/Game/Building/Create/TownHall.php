<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;
use Model\Game\Building\TownHall as ModelTownHall;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;

class TownHall extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        
        ModelTownHall::createFromValues(array(
                "instance" => $inst,
                "name"     => $specific["name"],
                "welcome"  => $specific["welcome"]
                )) ;
        
        return ;
    }

    public function getSpecificFieldset() {
        
        $fieldset = new FieldSet(\I18n::TOWNHALL_PARAMETERS()) ;
        $fieldset->addInput("name", new Text(\I18n::CITY_NAME())) ;
        $fieldset->addInput("welcome", new FullHtml(\I18n::CITY_WELCOME())) ;
        return $fieldset ;
    }
    
}
