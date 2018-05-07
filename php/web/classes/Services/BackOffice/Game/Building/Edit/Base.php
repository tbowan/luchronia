<?php

namespace Services\BackOffice\Game\Building\Edit ;

use Model\Game\Building\Instance;
use Model\Game\Building\Type;
use Quantyl\Form\Fields\Integer;
use Quantyl\Form\FieldSet;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

abstract class Base extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Instance::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $general = $form->addInput("general", new FieldSet(\I18n::BUILDING_EDIT_GENERAL())) ;
        $general->addInput("type",   new Select(Type::getBddTable(), \I18n::BUILDING_TYPE(), true))
                ->setValue($this->id->type);
        $general->addInput("level",  new Integer(\I18n::BUILDING_LEVEL(), true))
                ->setValue($this->id->level);
        $general->addInput("health", new \Quantyl\Form\Fields\Float(\I18n::BUILDING_HEALTH(), true))
                ->setValue($this->id->health);
        $general->addInput("barricade", new \Quantyl\Form\Fields\Float(\I18n::BUILDING_BARRICADE(), true))
                ->setValue($this->id->barricade);
        
        
        if ($this->isTrading()) {
            $tax = \Model\Game\Tax\Tradable::GetFromInstance($this->id) ;
            $trading = $form->addInput("trading", new FieldSet(\I18n::TRADING_PARAMETER())) ;
            $trading->addInput("tax_order", new \Quantyl\Form\Fields\Float(\I18n::TAX_ORDER()))
                    ->setValue($tax->order) ;
            $trading->addInput("tax_trans", new \Quantyl\Form\Fields\Float(\I18n::TAX_TRANS()))
                    ->setValue($tax->trans) ;
        }
        
        $specific = $this->getSpecificFieldset() ;
        if ($specific != null) {
            $form->addInput("specific", $specific) ;
        }
    }

        
    public function isTrading() {
        return $this->id->job->tradable ;
    }
    
    public function onProceed($data) {
        $specific = isset($data["specific"]) ? $data["specific"] : null ;
        $this->doSpecificStuff($specific, $this->id) ;
                
        if ($this->isTrading()) {
            $tax = \Model\Game\Tax\Tradable::GetFromInstance($this->id) ;
            $tax->order = $data["trading"]["tax_order"] ;
            $tax->trans = $data["trading"]["tax_trans"] ;
            $tax->update() ;
        }
        
        $general = $data["general"] ;
        $this->id->type      = $general["type"] ;
        $this->id->level     = $general["level"] ;
        $this->id->health    = $general["health"] ;
        $this->id->barricade = $general["barricade"] ;
        $this->id->update() ;
    }
    
    public abstract function getSpecificFieldset() ;
    
    public abstract function doSpecificStuff(
            $specific,
            Instance $inst
            ) ;
    
    public function getTitle() {
        return \I18n::TITLE_Services_BackOffice_Game_Building_Edit_Defaut($this->id->job->getName()) ;
    }
    
}
