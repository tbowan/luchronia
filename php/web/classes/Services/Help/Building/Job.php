<?php

namespace Services\Help\Building ;

class Job extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Building\Job::getBddTable())) ;
    }
    
    public function getView() {
        if ($this->id === null) {
            return new \Answer\Widget\Help\Building\Job\ShowAll() ;
        } else {
            return new \Answer\Widget\Help\Building\Job\ShowOne($this->id);
        }
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return \I18n::TITLE_HELP(\I18n::BUILDING_JOB(), $this->id->getName()) ; 
        }
    }
    
}
