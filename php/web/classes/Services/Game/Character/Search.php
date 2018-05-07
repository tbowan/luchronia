<?php

namespace Services\Game\Character ;

class Search extends \Services\Base\Character {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::SEARCH_FOR_A_CHARACTER()) ;
        $form->addInput("name", new \Quantyl\Form\Fields\Text(\I18n::NAME())) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $this->setAnswer(new \Widget\Game\Character\SearchResult($data["name"])) ;
    }
    
}
