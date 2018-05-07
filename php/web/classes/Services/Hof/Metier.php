<?php

namespace Services\Hof ;

class Metier extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("metier", new \Quantyl\Form\Model\Id(\Model\Game\Skill\Metier::getBddTable())) ;
    }
    
    public function getView() {
        return new \Widget\Hof\Metier($this->metier) ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_Services_Hof_Metier($this->metier->getName()) ;
    }
}
