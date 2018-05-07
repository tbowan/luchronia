<?php

namespace Services\Game\Post ;

class Parcel extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("parcel", new \Quantyl\Form\Model\Id(\Model\Game\Post\Parcel::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->parcel->recipient->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Post\Parcel($this->parcel, $this->getCharacter()) ;
    }
    
    
}
