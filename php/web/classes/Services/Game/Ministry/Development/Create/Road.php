<?php

namespace Services\Game\Ministry\Development\Create ;

class Road extends \Services\Base\Minister {
    
    private $_job ;
    private $_type ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function init() {
        parent::init();
        
        $this->_job  = \Model\Game\Building\Job::GetByName("Road") ;
        $this->_type = \Model\Game\Building\Type::GetByName("Misc") ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (\Model\Game\Building\Instance::HasCityJob($this->city, $this->_job)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_ALREADY_HAVE_ROAD()) ;
        }
    }
    
    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Development") ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CREATE_ROAD_MESSAGE() . $this->getCostMessage()) ;
    }
    
    public function onProceed($data) {
        
        $instance = \Model\Game\Building\Instance::createFromValues(array(
            "job"       => \Model\Game\Building\Job::GetByName("Site")  ,
            "type"      => $this->_type,
            "city"      => $this->city,
            "level"     => 1,
            "health"    => 10
        )) ;
        
        $site = \Model\Game\Building\Site::createFromValues(array(
            "instance"      => $instance,
            "job"           => $this->_job,
            "last_update"   => time()
        )) ;
        
        foreach ($this->getCosts() as $id => $amount) {
            $item = \Model\Game\Ressource\Item::GetById($id) ;
            \Model\Game\Building\Need::createFromValues(array(
                "site"      => $site,
                "item"      => $item,
                "needed"    => $amount,
                "provided"  => 0
            )) ;
        }
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Building?id={$instance->id}"));
    }
    
    private $_costs ;
    
    public function getCosts() {
        if (! isset($this->_costs)) {
            $this->_costs = \Model\Game\Building\Map::getCostBase($this->_job, $this->_type, 1) ;
        }
        return $this->_costs ;
    }
    
    public function getCostMessage() {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT()
        )) ;
        foreach ($this->getCosts() as $id => $amount) {
            $item = \Model\Game\Ressource\Item::GetById($id) ;
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                $amount
            )) ;
        }
        return "" . $table ;
        
    }
    
    
    
}