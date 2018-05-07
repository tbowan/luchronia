<?php

namespace Services\BackOffice\Game\Building\Delete ;

use Model\Game\Building\Instance;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

abstract class Base extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Instance::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM(
                $this->id->job->getName() .
                " / " .
                $this->id->type->getName()
                )) ;
        
        $specific = $this->getSpecificFieldset() ;
        if ($specific != null) {
            $form->addInput("specific", $specific) ;
        }
    }

    
    public function onProceed($data) {
        $specific = isset($data["specific"]) ? $data["specific"] : null ;
        $this->doSpecificStuff($specific, $this->id) ;
        
        $obj = $this->id->getTrueObject() ;
        $obj->destroy() ;
    }
    
    public abstract function getSpecificFieldset() ;
    
    public abstract function doSpecificStuff(
            $specific,
            Instance $inst
            ) ;
    
}
