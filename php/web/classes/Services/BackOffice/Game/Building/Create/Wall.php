<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Enums\Door;
use Model\Game\Building\Instance;
use Model\Game\Building\Wall as MWall;
use Quantyl\Exception\Http\ClientForbidden;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Fields\Float;
use Quantyl\Form\FieldSet;
use Quantyl\Form\Model\EnumSimple;

class Wall extends Base {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $wall = MWall::GetFromCity($this->city) ;
        if ($wall != null) {
            throw new ClientForbidden(\I18n::EXP_CANNOT_MULTIPLE_WALL()) ;
        }
    }
    
    public function doSpecificStuff($specific, Instance $inst) {
        MWall::createFromValues(array(
            "instance"  => $inst,
            "door"      => $specific["door"],
            "message"   => $specific["message"],
        )) ;
    }

    public function getSpecificFieldset() {
        $fieldset = new FieldSet(\I18n::CITYWALL()) ;
        $fieldset->addInput("door",     new EnumSimple(Door::getBddTable(), \I18n::DOOR_WALL())) ;
        $fieldset->addInput("message",  new FilteredHtml(\I18n::MESSAGE())) ;
        return $fieldset ;
    }

}
