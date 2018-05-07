<?php

namespace Services\BackOffice\Game\Building\Create ;

use Model\Game\Building\Instance;
use Model\Game\Building\Job;
use Model\Game\Building\Type;
use Model\Game\City;
use Quantyl\Form\Fields\Integer;
use Quantyl\Form\Fields\Float;
use Quantyl\Form\FieldSet;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Name;
use Quantyl\Form\Model\Select;

abstract class Base extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city", new Id(City::getBddTable(), "", true)) ;
        $form->addInput("job",  new Name(Job::getBddTable(), "", true)) ;
    }
    
    public function fillDataForm(Form &$form) {
        $general = $form->addInput("general", new FieldSet(\I18n::BUILDING_CREATE_GENERAL())) ;
        $general->addInput("type",      new Select(Type::getBddTable(), \I18n::BUILDING_TYPE(), true)) ;
        $general->addInput("level",     new Integer(\I18n::BUILDING_LEVEL(), true)) ;
        $general->addInput("health",    new Float(\I18n::BUILDING_HEALTH(), true)) ;
        $general->addInput("barricade", new Float(\I18n::BUILDING_BARRICADE(), true)) ;
        
        if ($this->isTrading()) {
            $trading = $form->addInput("trading", new FieldSet(\I18n::TRADING_PARAMETER())) ;
            $trading->addInput("tax_order", new \Quantyl\Form\Fields\Float(\I18n::TAX_ORDER())) ;
            $trading->addInput("tax_trans", new \Quantyl\Form\Fields\Float(\I18n::TAX_TRANS())) ;
        }
        
        $specific = $this->getSpecificFieldset() ;
        if ($specific != null) {
            $form->addInput("specific", $specific) ;
        }
    }
    
    public function isTrading() {
        return $this->job->tradable ;
    }
    
    public function onProceed($data) {
        
        $general = $data["general"] ;
        
        $res = Instance::createFromValues(array(
            "city"      => $this->city,
            "job"       => $this->job,
            "type"      => $general["type"],
            "level"     => $general["level"],
            "health"    => $general["health"],
            "barricade" => $general["barricade"]
        )) ;

        if ($this->isTrading()) {
            \Model\Game\Tax\Tradable::createFromValues(array(
                "instance" => $res,
                "order" => $data["trading"]["tax_order"],
                "trans" => $data["trading"]["tax_trans"]
            )) ;
        }
        
        $specific = isset($data["specific"]) ? $data["specific"] : null ;
        $this->doSpecificStuff($specific, $res) ;
    }
    
    public abstract function getSpecificFieldset() ;
    
    public abstract function doSpecificStuff(
            $specific,
            Instance $inst
            ) ;
    
    public function getTitle() {
        return \I18n::TITLE_Services_BackOffice_Game_Building_Create_Defaut($this->job->getName()) ;
    }
    
}
