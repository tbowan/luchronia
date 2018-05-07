<?php

namespace Answer\View\Game\Building ;

class OutSide extends \Answer\View\Base {
    
    private $_character ;
    private $_city ;
    private $_job ;
    private $_admin ;
    
    public function __construct($service, \Model\Game\Character $character, \Model\Game\City $c, $admin) {
        parent::__construct($service) ;
        
        $this->_character   = $character ;
        $this->_city        = $c ;
        $this->_job         = \Model\Game\Building\Job::GetByName("OutSide") ;
        $this->_admin       = $admin ;
    }
   
    public function getTplContent() {
        
        return ""
                . '<div class="card-1-2">'
                . new \Answer\Widget\Game\Building\Outside\Description($this->_job)
                . new \Answer\Widget\Game\Building\Outside\Information($this->_city, $this->_job)
                . new \Answer\Widget\Game\Building\Outside\Prospection($this->_city, $this->_character)
                . new \Answer\Widget\Game\Building\Outside\Stocks($this->_city, $this->_admin)
                . '</div>'
                . '<div class="card-1-2">'
                . new \Answer\Widget\Game\Building\Outside\SkillList($this->_city, $this->_job, $this->_character)
                . '</div>'
                ;
    }
    
}
