<?php

namespace Services\Game\Ministry\Development\Delete ;

use Model\Game\Building\Instance;
use Model\Game\Politic\Ministry;
use Model\Game\Ressource\City;
use Model\Game\Ressource\Item;
use Quantyl\Answer\ListAnswer;
use Quantyl\Answer\Message;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\XML\Html\Table;

abstract class Base extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Development") ;
    }
    
    public function init() {
        parent::init();
        
        $class = preg_replace("/.*\\\\/", "", get_class($this)) ;
        
        $jobname = $this->instance->job->name ;
        $classname  = "\\Services\\Game\\Ministry\\Development\\Delete\\$jobname" ;
                
        if ($class == "Defaut" && class_exists($classname)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        } else if ($class != "Defaut" && $jobname != $class) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
        
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
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(
                $this->getGeneralInformation() .
                $this->getSpecificInformation()
                ) ;
        
        $g = $this->getGeneralFieldset() ;
        if ($g !== null) {
            $form->addInput("general", $g) ;
        }
        
        $s = $this->getSpecificFieldset() ;
        if ($s !== null) {
            $form->addInput("specific", $s) ;
        }
    }
    
    public function onProceed($data) {
        
        $answer = new ListAnswer() ;
        
        $s = (isset($data["specific"]) ? $data["specific"] : null) ;
        $sa = $this->doSpecificStuff($s) ;
        if ($sa != null) {
            $answer->addAnswer($sa) ;
        }
        
        $g = (isset($data["general"]) ? $data["general"] : null) ;
        $ga = $this->doGeneralStuff($g) ;
        if ($ga != null) {
            $answer->addAnswer($ga) ;
        }
        
        $this->setAnswer($answer) ;
        
    }

    public function getGeneralInformation() {
        
        $this->instance->getRessources() ;
        
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT()
        )) ;
        
        foreach ($this->instance->getRessources() as $id => $amount) {
            $item = Item::GetById($id) ;
            $table->addRow(array(
                $item->getImage("icone-med") . " " . $item->getName(),
                $amount
            )) ;
        }
        
        $res = \I18n::BUILDING_DELETE_MESSAGE() ;
        $res .= "<h2>" . \I18n::RESSOURCES() . "</h2>" ;
        $res .= $table ;
        
        return $res ;
        
    }
    
    public function getGeneralFieldset() {
        return null ;
    }
    
    public function doGeneralStuff($general) {
        
        // Get ressources
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::PRIVATE_STOCKS()
        )) ;
        
        foreach ($this->instance->getRessources() as $id => $amount) {
            $item = Item::GetById($id) ;
            
            City::Donate($this->instance->city, $item, $amount) ;
            
            $table->addRow(array(
                $item->getImage("icone-med") . " " . $item->getName(),
                $amount
            )) ;
            
        }
        
        // Delete instance
        $obj = $this->instance->getTrueObject() ;
        $obj->destroy() ;
        
        // Message
        
        $res = \I18n::BUILDING_DELETE_MESSAGE_NORMAL() ;
        $res .= "<h2>" . \I18n::RESSOURCES() . "</h2>" ;
        $res .= $table ;
        
        return new Message($res) ;
    }

    
    public abstract function getSpecificInformation() ;
    
    public abstract function getSpecificFieldset() ;
    
    public abstract function doSpecificStuff($specific) ;
    
    public function getTitle() {
        return \I18n::TITLE_Services_Game_Ministry_Development_Delete($this->instance->job->getName()) ;
    }
    
}
