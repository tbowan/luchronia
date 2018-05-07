<?php
namespace Answer\View\Forum ;

class CategoryRoot extends \Answer\View\Base {
    
    private $_me ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service) {
        parent::__construct($service);
        $this->_me       = $this->getCharacter() ;
    }
    
    public function getCategories() {
        $lang = \Model\I18n\Lang::GetCurrent() ;

        $categories = array() ;
        foreach (\Model\Forum\Category::GetRoots($lang) as $candidate) {
            if ($candidate->canView($this->getUser())) {
                $categories[] = $candidate ;
            }
        }
        return $categories ;
    }
    
    public function getGlobalCategorySection() {
        $categories = $this->getCategories() ;
        $widget     = new \Answer\Widget\Forum\CategoryTable($categories, $this->_me) ;
        
        $stats  = \I18n::THREADS() . " : " . \Model\Forum\Thread::CountAll() . " " ;
        $stats .= \I18n::POSTS() . " : " . \Model\Forum\Post::CountAll() ;
        
        $meta = "" ;
        $meta .= new \Quantyl\XML\Html\A("/Forum/Mine", \I18n::FORUM_MINE()) ;
        $meta .= ", " ;
        $meta .= new \Quantyl\XML\Html\A("/Forum/Last", \I18n::FORUM_LAST()) ;
        
        $section    =  new \Answer\Widget\Misc\Section(
                                \I18n::GLOBAL_CATEGORIES(),
                                $meta,
                                $stats,
                                $widget,
                                "") ;
        return $section ;
    }
    
    private function getCities() {
        if ($this->_me == null) {
            return array() ;
        } else {
            
            $res = array() ;
            
            // insert position :
            $res[$this->_me->position->id] = $this->_me->position ;
            
            // insert citizenship
            foreach (\Model\Game\Citizenship::GetFromCitizen($this->_me) as $citizen) {
                $city = $citizen->city ;
                $res[$city->id] = $city ;
            }
            
            return $res ;
            
        }
    }
    
    public function addCity(\Model\Game\City $c) {
        
        $instances = \Model\Game\Building\Instance::GetFromCityAndJob($c, \Model\Game\Building\Job::GetByName("Forum")) ;
        
        $res = "" ;
        foreach ($instances as $forum) {
            $res .= new \Answer\Widget\Game\Forum\Root($forum, $this->_me) ;
        }
        
        if ($res !== "") {
            $res = new \Answer\Widget\Misc\Section($c->getName(), "", "", $res, "") ;
        }
        return $res ;
        
    }
    
    public function getTplContent() {
        
        $res = "" ;
        $res .= $this->getGlobalCategorySection() ;
        
        foreach ($this->getCities() as $c) {
            $res .= $this->addCity($c) ;
        }
        
        return $res ;
    }
    
}
