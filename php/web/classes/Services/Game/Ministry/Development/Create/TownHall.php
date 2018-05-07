<?php

namespace Services\Game\Ministry\Development\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;

class TownHall extends Base {

    public function needMoreSlots() {
        return
                parent::needMoreSlots() ||
                ! $this->city->hasTownHall() ;
    }
    
    public function getSpecificFieldset() {
        $fieldset = new FieldSet(\I18n::TOWNHALL_PARAMETERS()) ;
        $fieldset->addInput("name", new Text(\I18n::CITY_NAME())) ;
        $fieldset->addInput("welcome", new \Quantyl\Form\Fields\FilteredHtml(\I18n::CITY_WELCOME())) ;
        return $fieldset ;
    }

    public function getSpecificMessage() {
        
    }

    public function doSpeficicStuff(Instance $i, $data) {
        \Model\Game\Building\TownHall::createFromValues(array(
                "instance" => $i,
                "name"     => $data["name"],
                "welcome"  => $data["welcome"]
                )) ;
        
        // MSG you are now king for 7 days
        return ;
    }

}
