<?php

namespace Services\Game\Ministry\Development\Upgrade ;

use Model\Game\Building\Instance;
use Model\Game\Building\Job;
use Model\Game\Building\Map;
use Model\Game\Building\Need;
use Model\Game\Building\Site;
use Model\Game\Politic\Ministry;
use Model\Game\Ressource\Inventory;
use Model\Game\Ressource\Item;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Fields\Submit;
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
        $form->addInput("inventory", new Id(Inventory::getBddTable())) ;
    }
    
    public function checkService() {
        $class = preg_replace("/.*\\\\/", "", get_class($this)) ;
        $jobname = $this->map->job->name ;
        $classname  = "\\Services\\Game\\Ministry\\Development\\Upgrade\\$jobname" ;
                
        if ($class == "Defaut" && class_exists($classname)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        } else if ($class != "Defaut" && $jobname != $class) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
    }

    public function checkMap() {
        $m = $this->map ;
        $i = $this->instance ;
        
        if (
                ! ($i->job->equals($m->job)  ) ||
                ! ($i->type->equals($m->type)) ||
                ! ($i->level < $m->level     )
           ) {
            throw new \Quantyl\Exception\Http\ClientBadRequest("map") ;
        }
    }
    
    public function init() {
        parent::init();
        $this->map = Map::getFromItem($this->inventory->item) ;
        $this->checkMap() ;
        $this->checkService() ;
    }
    
    public $map ;

    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        // character own the map
        if (! $me->equals($this->inventory->character)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        // Character present in the city
        if (! $me->position->equals($this->instance->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        } else if ($this->instance->health <= 0) {
            throw new \Quantyl\Exception\Http\ClientNotFound(\I18n::EXP_NO_SUCH_BUILDING()) ;
        }
    }
    
    private $_costs ;
    private $_ressources ;
    
    public function getCosts() {
        if ($this->_costs == null) {
            $map = Map::getFromItem($this->map->item) ;
            $this->_costs = $map->getCosts() ;
        }
        return $this->_costs ;
    }
    
    public function getRessources() {
        if ($this->_ressources == null) {
            $this->_ressources = $this->instance->getRessources() ;
        }
        return $this->_ressources ;
    }
    
    public function getGeneralMessage() {
        $map = Map::getFromItem($this->map->item) ;
        
        $res = \I18n::BUILDING_UPGRADE_BASE_MESSAGE() ;
        $res .= "<h2>" . \I18n::INFORMATIONS() . "</h2>" ;
            
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_JOB() . \I18n::HELP("/Wiki/") . " :</strong> " . $map->job->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_TYPE()  . \I18n::HELP("/Wiki/") . " :</strong> " . $map->type->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::LEVEL()  . \I18n::HELP("/Wiki/") . " :</strong> " . $map->level . "</li>" ;
        $res .= "<li><strong>" . \I18n::HEALTH()  . \I18n::HELP("/Wiki/") . " :</strong> " . $map->getHealth() . "</li>" ;
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
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                $amount,
                $bonus[$id],
                $amount - $bonus[$id]
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
        
        $form->addInput("send", new Submit(\I18n::SEND())) ;
        return $form ;
        
    }
    
    public function onProceed($data) {
        
        $this->instance->job    = Job::GetByName("Site") ;
        $this->instance->level  = $this->map->level ;
        $this->instance->health = max(10, $this->instance->health) ;
        $this->instance->update() ;
        
        // Create building site
        $site = Site::createFromValues(array(
            "instance"      => $this->instance,
            "job"           => $this->map->job,
            "last_update"   => time()
        )) ;
        
        $bonus = $this->getRessources() ;
        foreach ($this->getCosts() as $id => $amount) {
            $item = Item::GetById($id) ;
            Need::createFromValues(array(
                "site"      => $site,
                "item"      => $item,
                "needed"    => $amount,
                "provided"  => $bonus[$id]
            )) ;
        }
        
        $this->inventory->amount -= 1 ;
        $this->inventory->update() ;
        
        // Do specific stuffs
        $this->doSpeficicStuff($this->instance, $data["specific"]) ;
        
        // Redirect to the instance
        $this->setAnswer(new Redirect("/Game/Building?id={$this->instance->id}"));
    }
    
    public abstract function getSpecificMessage() ;
    
    public abstract function getSpecificFieldSet() ;
    
    public abstract function doSpeficicStuff(Instance $i, $data) ;

    public function getTitle() {
        return \I18n::TITLE_Services_Game_Ministry_Development_Upgrade($this->instance->job->getName()) ;
    }
    
}
