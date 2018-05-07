<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Enums\Door;
use Model\Game\Building\Instance;
use Model\Game\Building\Wall as MWall;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\FieldSet;
use Quantyl\Form\Model\EnumSimple;

class Wall extends Base {
    
    public function doSpecificStuff($specific, Instance $inst) {
        $wall = MWall::GetFromInstance($this->id) ;
        $wall->door     = $specific["door"] ;
        $wall->message  = $specific["message"] ;
        $wall->update() ;
    }

    public function getSpecificFieldset() {
        $wall = MWall::GetFromInstance($this->id) ;
        $fieldset = new FieldSet(\I18n::CITYWALL()) ;
        $fieldset->addInput("door",     new EnumSimple(Door::getBddTable(), \I18n::DOOR_WALL()))
                 ->setValue($wall->door);
        $fieldset->addInput("message",  new FilteredHtml(\I18n::MESSAGE()))
                 ->setValue($wall->message);
        return $fieldset ;
    }

}
