<?php

namespace Services\Game\Ministry\Development\Upgrade ;

class Misc extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }
    
    public function getCity() {
        return $this->instance->city ;
    }
    
    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Development") ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->instance->level >= $this->instance->job->level) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_UPGRADE_ALREADY_MAX()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::UPGRADE_MISC_MESSAGE() . $this->getCostMessage()) ;
    }
    
    private $_costs ;
    
    public function getCosts() {
        if (! isset($this->_costs)) {
            $this->_costs = \Model\Game\Building\Map::getCostBase($this->instance->job, $this->instance->type, $this->instance->level + 1) ;
        }
        return $this->_costs ;
    }
    
    public function getCostMessage() {
        
        $level   = $this->instance->level + 1;
        $lp      = $level * ($level + 1) / 2 ;
        $health  = $this->instance->job->health * $lp ;
        $percent = round($this->instance->health / $health, 2) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::COST_BASE(),
            \I18n::COST_BONUS(),
            \I18n::COST_FINAL()
        )) ;
        
        foreach ($this->getCosts() as $id => $amount) {
            $item = \Model\Game\Ressource\Item::GetById($id) ;
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                $amount,
                $amount * $percent,
                $amount * (1 - $percent)
            )) ;
        }
        return "" . $table ;
        
    }
    
    public function onProceed($data) {
        $level   = $this->instance->level + 1;
        $lp      = $level * ($level + 1) / 2 ;
        $health  = $this->instance->job->health * $lp ;
        $percent = round($this->instance->health / $health, 2) ;
        
        
        // Create building site
        $site = \Model\Game\Building\Site::createFromValues(array(
            "instance"      => $this->instance,
            "job"           => $this->instance->job,
            "last_update"   => time()
        )) ;
        
        $this->instance->job    = \Model\Game\Building\Job::GetByName("Site") ;
        $this->instance->level  = $this->instance->level + 1 ;
        $this->instance->health = max(10, $this->instance->health) ;
        $this->instance->update() ;
        
        
        foreach ($this->getCosts() as $id => $amount) {
            $item = \Model\Game\Ressource\Item::GetById($id) ;
            \Model\Game\Building\Need::createFromValues(array(
                "site"      => $site,
                "item"      => $item,
                "needed"    => $amount,
                "provided"  => round($amount * $percent, 2)
            )) ;
        }
        // Do specific stuffs
        $this->doSpeficicStuff($this->instance, $data) ;
        
        // Redirect to the instance
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Building?id={$this->instance->id}"));
    }
    
    public function doSpeficicStuff(\Model\Game\Building\Instance $instance, $data) {
        return ;
    }
    
}
