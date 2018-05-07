<?php

namespace Services\Game\Ministry\Development\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;

class Post extends Base {

    public function getSpecificFieldset() {
        $fieldset = new FieldSet(\I18n::POST_PARAMETERS()) ;
        $fieldset->addInput("cost_mail", new \Quantyl\Form\Fields\Float(\I18n::COST_MAIL())) ;
        $fieldset->addInput("cost_parcel", new \Quantyl\Form\Fields\Float(\I18n::COST_PARCEL())) ;
        return $fieldset ;
    }

    public function getSpecificMessage() {
        
    }

    public function doSpeficicStuff(Instance $i, $data) {
        \Model\Game\Building\Post::createFromValues(array(
                "instance"      => $i,
                "cost_mail"     => $data["cost_mail"],
                "cost_parcel"   => $data["cost_parcel"],
                )) ;
        return ;
    }

}
