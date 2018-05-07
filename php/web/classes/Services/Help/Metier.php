<?php


namespace Services\Help ;

class Metier extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Skill\Metier::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if ($this->id == null) {
            return new \Answer\View\Help\AllMetier($this, $char);
        } else {
            return new \Answer\View\Help\Metier($this, $this->id, $char);
        }
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return \I18n::TITLE_HELP(\I18n::METIER(), $this->id->getName()) ; 
        }
    }
    
}