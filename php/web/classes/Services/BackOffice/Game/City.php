<?php

namespace Services\BackOffice\Game ;

use Model\Game\City as MCity;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Widget\BackOffice\Game\CityDetail;

class City extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(MCity::getBddTable())) ;
    }

    public function getView() {
        return new CityDetail($this->id) ;
    }
    
}
