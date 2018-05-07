<?php

namespace Services\Help ;

class Skill extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Skill\Skill::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        return \Answer\View\Help\Skill\Factory::getWidget($this->id, $this, $char) ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP(\I18n::SKILL(), $this->id->getName()) ;
    }
    
}
