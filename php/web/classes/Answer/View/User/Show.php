<?php

namespace Answer\View\User ;

class Show extends \Answer\View\Base {
    
    private $_user ;
    
    public function __construct(\Services\Base\Character $service, \Model\Identity\User $user) {
        parent::__construct($service);
        $this->_user    = $user ;
    }
    
    public function getTplContent() {
        $res = "" ;
        $res .= new \Answer\Widget\User\Identity($this->_user, "card-1-3") ;
        $res .= new \Answer\Widget\User\Adress($this->_user, "card-1-3") ;
        $res .= new \Answer\Widget\User\Authentication($this->_user, "card-1-3") ;
        $res .= new \Answer\Widget\User\Notifications($this->_user, "card-1-3") ;
        $res .= new \Answer\Widget\User\Quanta($this->_user, "card-1-3") ;
        $res .= new \Answer\Widget\User\Sponsor($this->_user, "card-1-3") ;
        
        $res .= new \Answer\Widget\User\Characters($this->_user, $this->getService()->getCharacter()) ;
        
        return $res ;
    }

    public function getTplMenu() {
        return \I18n::LM_MAIN() ;
    }

}
