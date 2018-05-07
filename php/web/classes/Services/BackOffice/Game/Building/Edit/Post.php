<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Game\Building\Instance;
use Quantyl\Form\FieldSet;

class Post extends Base {

    public function doSpecificStuff($specific, Instance $inst) {
        $post = \Model\Game\Building\Post::GetFromInstance($inst) ;
        $post->cost_mail    = $specific["cost_mail"] ;
        $post->cost_parcel  = $specific["cost_parcel"] ;
        $post->update() ;
    }

    public function getSpecificFieldset() {
        $post = \Model\Game\Building\Post::GetFromInstance($this->id) ;
        
        $fieldset = new FieldSet(\I18n::POST_PARAMETERS()) ;
        $fieldset->addInput("cost_mail", new \Quantyl\Form\Fields\Float(\I18n::COST_MAIL()))
                 ->setValue($post->cost_mail);
        $fieldset->addInput("cost_parcel", new \Quantyl\Form\Fields\Float(\I18n::COST_PARCEL()))
                 ->setValue($post->cost_parcel);
        return $fieldset ;
        
    }
    
}
