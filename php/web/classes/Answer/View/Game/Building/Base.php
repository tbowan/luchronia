<?php

namespace Answer\View\Game\Building ;

use Model\Game\Building\Instance;


class Base extends \Answer\View\Base {
    
    protected $_instance ;
    protected $_character ;
    
    public function __construct($service, Instance $instance, \Model\Game\Character $c) {
        parent::__construct($service) ;
        $this->_instance = $instance ;
        $this->_character = $c ;
    }

    public function getSpecific() {
        return "" ;
    }
    
    public function getTradingSkills() {
        if ($this->_instance->job->tradable) {
            return new \Answer\Widget\Game\Building\TradingSkills($this->_instance) ;
        } else {
            return "" ;
        }
    }
    
    public function getTplContent() {
        return ""
                . '<div class="card-1-2">'
                . new \Answer\Widget\Game\Building\Description($this->_instance)
                . new \Answer\Widget\Game\Building\Information($this->_instance)
                . '</div>'
                . '<div class="card-1-2">'
                . $this->getSpecific()
                . $this->getTradingSkills()
                . new \Answer\Widget\Game\Building\SkillList($this->_instance, $this->_character)
                . '</div>'
                ;
    }
    
}
