<?php

namespace Services\Game\Ministry\Development\Create ;

use Form\MapChoser;
use Model\Game\Building\Map;
use Model\Game\City;
use Model\Game\Politic\Ministry;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Main extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Development") ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city", new Id(City::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::BUILDING_CREATION_CHOSEMAP_MESSAGE()) ;
        $form->addInput("map", new MapChoser($this->getCharacter(), \I18n::BUILDING_MAP())) ;
    }
    
    public function onProceed($data) {
        $inventory  = $data["map"] ;
        $map        = Map::getFromItem($inventory->item) ;
        $jobname    = $map->job->name ;
        
        $params     = "?city={$this->city->id}&inventory={$inventory->id}" ;
        
        $classname  = "\\Services\\Game\\Ministry\\Development\\Create\\$jobname" ;
        $best       = "/Game/Ministry/Development/Create/$jobname" ;
        $defaut     = "/Game/Ministry/Development/Create/Defaut" ;
        
        if (class_exists($classname)) {
            $this->setAnswer(new Redirect("{$best}{$params}")) ;
        } else {
            $this->setAnswer(new Redirect("{$defaut}{$params}")) ;
        }
        
    }
}
