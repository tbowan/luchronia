<?php

namespace Answer\View\Forum ;

class Category extends AbstractCategory {
    
    private $_category ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\Forum\Category $category) {
        $this->_category = $category ;
        parent::__construct($service);
    }
    
    public function getCategories() {
        $categories = array() ;
        foreach ($this->_category->getChildren() as $cand) {
            if ($cand->canView($this->getUser())) {
                $categories[] = $cand ;
            }
        }
        return $categories ;
    }
    
    public function getThreads() {
        return $this->_category->getThreads() ;
    }
    
    public function getContentThreads() {
        $res = "" ;
        $res .= new \Quantyl\XML\Html\A("/Forum/AddThread?category={$this->_category->id}", \I18n::ADD_THREAD()) ;
        
        $threads = $this->getThreads() ;
        if (count($threads) > 0) {
            $widget = new \Answer\Widget\Forum\ThreadTable($threads, $this->getCharacter()) ;
            $res .= $widget ;
        }
        
        return new \Answer\Widget\Misc\Section(
                    \I18n::THREADS(),
                    "",
                    "",
                    $res,
                    "") ;
    }
    
    public function getTplContent() {
        $res = parent::getTplContent();
        $res .= $this->getContentThreads() ;
        return $res ;
    }
    
}
