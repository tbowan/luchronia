<?php

namespace Services\Game\Ministry\Development\Create ;

use Model\Game\Building\Instance;
use Model\Game\Building\Job;
use Model\Game\Building\Map;
use Model\Game\Building\Need;
use Model\Game\Building\Site;
use Model\Game\City;
use Model\Game\Politic\Ministry;
use Model\Game\Ressource\Inventory;
use Model\Game\Ressource\Item;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Quantyl\XML\Html\Table;

abstract class Base extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Development") ;
    }
    
    public function checkService() {
        $class = preg_replace("/.*\\\\/", "", get_class($this)) ;
        $jobname = $this->map->job->name ;
        $classname  = "\\Services\\Game\\Ministry\\Development\\Create\\$jobname" ;
                
        if ($class == "Defaut" && class_exists($classname)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        } else if ($class != "Defaut" && $jobname != $class) {
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
    }
    
    public function init() {
        parent::init();
        $this->map = Map::getFromItem($this->inventory->item) ;
        $this->checkService() ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city", new Id(City::getBddTable())) ;
        $form->addInput("inventory",  new Id(Inventory::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        // character own the map
        if (! $me->equals($this->inventory->character)) {
            throw new \Exception() ;
        }
        // Character present in the city
        if (! $me->position->equals($this->city)) {
            throw new \Exception(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        }
        
        // Check remain slots
        if ($this->needMoreSlots()) {
            throw new \Exception(\I18n::EXP_NEED_MORE_CITY_SLOTS()) ;
        }
    }
    
    public function needMoreSlots() {
        $used = $this->city->getSlotUsed() ;
        $available = $this->city->getSlotCount() ;
        return $used >= $available ;
    }
    
    private $_costs ;
    
    public function getCosts() {
        if ($this->_costs == null) {
            $this->_costs = $this->map->getCosts() ;
        }
        return $this->_costs ;
    }
    
    public function getGeneralMessage() {
        
        $res = \I18n::BUILDING_CREATE_BASE_MESSAGE() ;
        $res .= "<h2>" . \I18n::INFORMATIONS() . "</h2>" ;
            
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_JOB() . \I18n::HELP("/Wiki/") . " :</strong> " . $this->map->job->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_TYPE()  . \I18n::HELP("/Wiki/") . " :</strong> " . $this->map->type->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::LEVEL()  . \I18n::HELP("/Wiki/") . " :</strong> " . $this->map->level . "</li>" ;
        $res .= "<li><strong>" . \I18n::HEALTH()  . \I18n::HELP("/Wiki/") . " :</strong> " . $this->map->getHealth() . "</li>" ;
        $res .= "</ul>" ;
        
        $res .= "<h2>" . \I18n::BUILDING_COSTS() . "</h2>" ;
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT()
        )) ;
        foreach ($this->getCosts() as $id => $amount) {
            $item = Item::GetById($id) ;
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                $amount
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
        
        if ($this->isTrading()) {
            $trading = $form->addInput("trading", new FieldSet(\I18n::TRADING_PARAMETER())) ;
            $trading->addInput("tax_order", new \Quantyl\Form\Fields\Float(\I18n::TAX_ORDER())) ;
            $trading->addInput("tax_trans", new \Quantyl\Form\Fields\Float(\I18n::TAX_TRANS())) ;
        }
        
        $specific = $this->getSpecificFieldSet() ;
        if ($specific != null) {
            $form->addInput("specific", $specific) ;
        }
    }
    
    public function isTrading() {
        return $this->map->job->tradable ;
    }
    
    public function onProceed($data) {
        // Create building site
        $instance = Instance::createFromValues(array(
            "job"       => Job::GetByName("Site") ,
            "type"      => $this->map->type,
            "city"      => $this->city,
            "level"     => $this->map->level,
            "health"    => 10
        )) ;
        
        $site = Site::createFromValues(array(
            "instance"      => $instance,
            "job"           => $this->map->job,
            "last_update"   => time()
        )) ;
        
        if ($this->isTrading()) {
            \Model\Game\Tax\Tradable::createFromValues(array(
                "instance" => $instance,
                "order" => $data["trading"]["tax_order"],
                "trans" => $data["trading"]["tax_trans"]
            )) ;
        }
        
        foreach ($this->getCosts() as $id => $amount) {
            $item = Item::GetById($id) ;
            Need::createFromValues(array(
                "site"      => $site,
                "item"      => $item,
                "needed"    => $amount,
                "provided"  => 0
            )) ;
        }
        
        $this->inventory->amount -= 1 ;
        $this->inventory->update() ;
        
        // Do specific stuffs
        $this->doSpeficicStuff($instance, $data["specific"]) ;
        
        // Redirect to the instance
        $this->setAnswer(new Redirect("/Game/Building?id={$instance->id}"));
    }
    
    public abstract function getSpecificMessage() ;
    
    public abstract function getSpecificFieldSet() ;
    
    public abstract function doSpeficicStuff(Instance $i, $data) ;
    
    public function getTitle() {
        return \I18n::TITLE_Services_Game_Ministry_Development_Create($this->map->job->getName()) ;
    }
}
