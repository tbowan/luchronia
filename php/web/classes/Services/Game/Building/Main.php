<?php

namespace Services\Game\Building ;

use Model\Game\Building\Instance;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Main extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Instance::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->id->health <= 0) {
            throw new \Quantyl\Exception\Http\ClientNotFound(\I18n::EXP_NO_SUCH_BUILDING()) ;
        }
    }
    
    public function getView() {
        
        $job  = $this->id->job->name ;
        $classpath = "\\Answer\\View\\Game\\Building\\" ;
        
        $classname = $classpath . $job ;
        $defaultname = $classpath . "Base" ;
        
        if (class_exists($classname)) {
            return new $classname($this, $this->id, $this->getCharacter()) ;
        } else {
            return new $defaultname($this, $this->id, $this->getCharacter()) ;
        }
        
    }
    
    public function getTitle() {
        if ($this->id !== null) {
            return $this->id->getName() ;
        } else {
            return parent::getTitle() ;
        }
    }
}
