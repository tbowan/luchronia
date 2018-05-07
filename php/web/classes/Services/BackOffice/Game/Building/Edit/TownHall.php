<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Game\Building\Instance;
use Model\Game\Building\TownHall as ModelTownHall;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;
use Quantyl\Request\Request;

class TownHall extends Base {

    public function doSpecificStuff($specific, Instance $inst) {
        $townhall = ModelTownHall::GetFromInstance($inst) ;
        $townhall->name = $specific["name"] ;
        $townhall->welcome = $specific["welcome"] ;
        $townhall->update() ;
    }

    public function getSpecificFieldset() {
        $townhall = ModelTownHall::GetFromInstance($this->id) ;
        
        $fieldset = new FieldSet(\I18n::TOWNHALL_PARAMETERS()) ;
        $fieldset->addInput("name", new Text(\I18n::CITY_NAME()))
                 ->setValue($townhall->name);
        $fieldset->addInput("welcome", new FullHtml(\I18n::CITY_WELCOME()))
                 ->setValue($townhall->welcome);
        return $fieldset ;
        
    }
    
}
