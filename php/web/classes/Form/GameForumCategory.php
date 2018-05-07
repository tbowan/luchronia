<?php

namespace Form ;

class GameForumCategory extends \Quantyl\Form\Model\Select {
    
    private $_instance ;
    
    public function __construct(\Model\Game\Building\Instance $forum, $label = null, $mandatory = false, $description = null) {
        $this->_instance = $forum ;
        parent::__construct(\Model\Game\Forum\Category::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $choices = array() ;
        foreach (\Model\Game\Forum\Category::GetFromInstance($this->_instance) as $category) {
            $choices[$category->id] = $category->getName() ;
        }
        return $choices ;
    }
    
}
