<?php

namespace Services\Game\Ministry\Development\Restore ;

use Model\Game\Building\Instance;
use Model\Game\Building\Job;
use Model\Game\Building\Need;
use Model\Game\Building\Site;
use Model\Game\Politic\Ministry;
use Model\Game\Ressource\Item;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Quantyl\XML\Html\Table;

abstract class Base extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Development") ;
    }
        
    public function fillParamForm(Form &$form) {
        $form->addInput("instance",  new Id(Instance::getBddTable())) ;
    }
    
    public function checkService() {
        $class = preg_replace("/.*\\\\/", "", get_class($this)) ;
        $jobname = $this->instance->job->name ;
        $classname  = "\\Services\\Game\\Ministry\\Development\\Restore\\$jobname" ;

        if ($class == "Defaut" && class_exists($classname)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        } else if ($class != "Defaut" && $jobname != $class) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
    }
    
    private $_costs ;
    private $_ressources ;
    
    public function init() {
        parent::init();
        $this->checkService() ;
        
        $this->_costs       = $this->instance->getCosts() ;
        $this->_ressources  = $this->instance->getRessources() ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        
        // Character present in the city
        if (! $me->position->equals($this->instance->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        } else if ($this->instance->health <= 0) {
            throw new \Quantyl\Exception\Http\ClientNotFound(\I18n::EXP_NO_SUCH_BUILDING()) ;
        }
    }
    
    public function getCosts() {
        return $this->_costs ;
    }
    
    public function getRessources() {
        return $this->_ressources ;
    }
    
    public function getGeneralMessage() {
        $inst = $this->instance ;
        
        $res = \I18n::BUILDING_RESTORE_BASE_MESSAGE() ;
        $res .= "<h2>" . \I18n::INFORMATIONS() . "</h2>" ;
            
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_JOB() . \I18n::HELP("/Wiki/") . " :</strong> " . $inst->job->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_TYPE()  . \I18n::HELP("/Wiki/") . " :</strong> " . $inst->type->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::LEVEL()  . \I18n::HELP("/Wiki/") . " :</strong> " . $inst->level . "</li>" ;
        $res .= "<li><strong>" . \I18n::HEALTH()  . \I18n::HELP("/Wiki/") . " :</strong> " . $inst->getMaxHealth() . "</li>" ;
        $res .= "</ul>" ;
        
        $res .= "<h2>" . \I18n::BUILDING_COSTS() . "</h2>" ;
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::COST_BASE(),
            \I18n::COST_BONUS(),
            \I18n::COST_FINAL()
        )) ;
        $bonus = $this->getRessources() ;
        foreach ($this->getCosts() as $id => $amount) {
            $item = Item::GetById($id, true) ;
            $b = (isset($bonus[$id]) ? $bonus[$id] : 0) ;
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                $amount,
                $b,
                $amount - $b
            )) ;
        }
        $res .= $table ;
        return $res ;
        
    }
    
    public function fillDataForm(Form &$form) {
        // Message
        $info  = $this->getGeneralMessage() ;
        $info .= $this->getSpecificMessage() ;
        
        $form->setMessage($info) ;
        
        $specific = $this->getSpecificFieldSet() ;
        if ($specific != null) {
            $form->addInput("specific", $specific) ;
        }
    }
    
    
    public function makeSite() {
        return Site::createFromValues(array(
            "instance"      => $this->instance,
            "job"           => $this->instance->job,
            "last_update"   => time()
        )) ;
    }
    
    public function getSiteHealth(Instance $i) {
        return max($i->health, $i->getMaxHealth() / 10) ;
    }
    
    public function onProceed($data) {
        
        $site = $this->makeSite() ;
        
        $this->instance->job    = Job::GetByName("Site") ;
        $this->instance->health = $this->getSiteHealth($this->instance) ;
        $this->instance->update() ;
        
        
        $bonus = $this->getRessources() ;
        foreach ($this->getCosts() as $id => $amount) {
            $item = Item::GetById($id) ;
            $b = (isset($bonus[$id]) ? $bonus[$id] : 0) ;
            Need::createFromValues(array(
                "site"      => $site,
                "item"      => $item,
                "needed"    => $amount,
                "provided"  => $b
            )) ;
        }
        
        // Do specific stuffs
        $this->doSpeficicStuff($this->instance, $data["specific"]) ;
        
        // Redirect to the instance
        $this->setAnswer(new Redirect("/Game/Building?id={$this->instance->id}"));
    }
    
    public abstract function getSpecificMessage() ;
    
    public abstract function getSpecificFieldSet() ;
    
    public abstract function doSpeficicStuff(Instance $i, $data) ;
    
    public function getTitle() {
        return \I18n::TITLE_Services_Game_Ministry_Development_Restore($this->instance->job->getName()) ;
    }
    
}
