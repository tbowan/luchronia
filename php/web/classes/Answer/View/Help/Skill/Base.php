<?php

namespace Answer\View\Help\Skill ;

class Base extends \Answer\View\Base {
    
    protected $_skill ;
    protected $_viewer ;
    
    public function __construct($service, \Model\Game\Skill\Skill $s, $viewer) {
        parent::__construct($service) ;
        $this->_skill  = $s ;
        $this->_viewer = $viewer ;
    }
    
    public function getTplContent() {
              
        return ""
                . new \Answer\Widget\Help\Skill\Description($this->_skill, "card-1-2")
                . new \Answer\Widget\Help\Skill\Learning($this->_skill, $this->_viewer, "card-1-2")
                . new \Answer\Widget\Help\Skill\Tools($this->_skill) // , "card-1-2")
                . $this->getSpecific("card-1-2")
                ;
    }
    
    public function getSpecific($class  = "") {
        return "" ;
    }
    
}
