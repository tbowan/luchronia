<?php

namespace Answer\Decorator ;

abstract class Base extends \Answer\View\Base {
    
    private $_msg ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, $msg) {
        parent::__construct($service);
        $this->_msg         = $msg ;
    }

    public function getTplContent() {
        if ($this->_msg instanceof \Answer\Widget\Misc\Section) {
            return $this->_msg ;
        } else {
            return new \Answer\Widget\Misc\Section($this->getService()->getTitle(), "", "", $this->_msg) ;
        }
    }

}
