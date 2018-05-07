<?php

namespace Answer\Widget\User\Quanta ;

class Main extends \Quantyl\Answer\Widget {
    
    private $_user ;
    
    public function __construct(\Model\Identity\User $user) {
        $this->_user = $user ;
    }
    
    public function getContent() {
        return ""
                . $this->getLevelPoints()
                . $this->getItems() ;
    }
    
    public function getItems() {
        $res = "<h2>" . \I18n::BUY_ITEM() . "</h2>" ;
        $res .= \I18n::BUY_ITEM_MSG() ;
        $res .= new \Quantyl\XML\Html\A("/User/Quanta/Item", \I18n::BUY_ITEM()) ;
        return $res ;
    }
    
    public function getLevelPoints() {
        $res = "<h2>" . \I18n::BUY_LEVEL_POINT() . "</h2>" ;
        $res .= \I18n::BUY_LEVEL_POINT_MSG() ;
        $res .= new \Quantyl\XML\Html\A("/User/Quanta/Level", \I18n::BUY_LEVEL_POINT()) ;
        return $res ;
    }
    
}
