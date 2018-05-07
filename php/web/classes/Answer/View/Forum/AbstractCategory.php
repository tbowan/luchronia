<?php

namespace Answer\View\Forum ;

abstract class AbstractCategory extends \Answer\View\Base {
    
    private $_category ;
    private $_user ;
    private $_me ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service) {
        parent::__construct($service, "");
        $this->_user     = $this->getUser() ;
        $this->_me       = $this->getCharacter() ;
    }
    
    public abstract function getCategories() ;
        
    public function getContentCategories() {
        $categories = $this->getCategories() ;
        if (count($categories) > 0) {
            $widget = new \Answer\Widget\Forum\CategoryTable($categories, $this->_me) ;
            return new \Answer\Widget\Misc\Section(
                    \I18n::CATEGORIES(),
                    "",
                    "",
                    $widget,
                    "") ;
        } else {
            return "" ;
        }
    }
    
    
    
    
    public function getTplContent() {
        $res = "" ;
        $res .= $this->getContentCategories() ;
        
        return $res ;
    }
    
}
