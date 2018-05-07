<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;
use Quantyl\Form\FieldSet;

class Post extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        
        \Model\Game\Building\Post::createFromValues(array(
                "instance"      => $inst,
                "cost_mail"     => $specific["cost_mail"],
                "cost_parcel"   => $specific["cost_parcel"],
                )) ;
        
        return ;
    }

    public function getSpecificFieldset() {
        
        $fieldset = new FieldSet(\I18n::POST_PARAMETERS()) ;
        $fieldset->addInput("cost_mail", new \Quantyl\Form\Fields\Float(\I18n::COST_MAIL())) ;
        $fieldset->addInput("cost_parcel", new \Quantyl\Form\Fields\Float(\I18n::COST_PARCEL())) ;
        return $fieldset ;
    }
    
}
