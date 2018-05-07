<?php

namespace Services\Game\Ministry\Building ;

use Model\Game\Building\Instance;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Main extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return $this->instance->job->ministry ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("instance", new Id(Instance::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->instance->health <= 0) {
            throw new \Quantyl\Exception\Http\ClientNotFound(\I18n::EXP_NO_SUCH_BUILDING()) ;
        }
    }
    
    public function getTitle() {
        return \I18n::TITLE_Service_Game_Ministry_Building($this->instance->job->getName()) ;
    }
        
    public function getView() {
        $jobname    = $this->instance->job->name ;
        
        
        $classpath = "\\Widget\\Game\\Ministry\\Building\\" ;
        $classname = $classpath . $jobname ;
        $defaut    = $classpath . "Base" ;
        
        
        
        if (class_exists($classname)) {
            return new $classname($this->instance, $this->getCharacter()) ;
        } else {
            return new $defaut($this->instance, $this->getCharacter()) ;
        }
    }
    
    
    
}
