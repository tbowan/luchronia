<?php

namespace Services\Game\Ministry\Development\Create ;

class Wall extends Base {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $wall = \Model\Game\Building\Wall::GetFromCity($this->getCity()) ;
        if ($wall != null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CANNOT_MULTIPLE_WALL()) ;
        }
    }
    
    public function doSpeficicStuff(\Model\Game\Building\Instance $i, $data) {
        \Model\Game\Building\Wall::createFromValues(array(
            "door" => \Model\Enums\Door::OPEN(),
            "message" => "",
        )) ;
    }

    public function getSpecificFieldSet() {
    }

    public function getSpecificMessage() {
    }
}
