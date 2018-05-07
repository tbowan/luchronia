<?php

namespace Services\BackOffice\Game ;

use Model\Game\Biome;
use Model\Game\City;
use Quantyl\Form\Fields\Submit;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;
use Quantyl\Request\Request;
use Quantyl\Service\EnhancedService;
use Widget\BackOffice\EditSuccess;

class ChangeCityBiome extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(City::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("biome", new Select(
                Biome::getBddTable(),
                \I18n::BIOME(),
                true))
             ->setValue($this->id->biome);
    }
    
    public function onProceed($data) {
        $this->id->biome = $data["biome"] ;
        $this->id->update() ;
    }
    
}
