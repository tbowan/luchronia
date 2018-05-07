<?php

namespace Answer\View ;

class Main extends Base {
    
    private $_lang ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\I18n\Lang $l) {
        parent::__construct($service) ;
        $this->_lang = $l ;
    }
    
    public function getAbout() {
        
        if ($this->_lang->mainpage !== null) {
            return new \Answer\Widget\Misc\Section(
                $this->_lang->mainpage->title,
                null,
                null,
                $this->_lang->mainpage->content,
                null) ;
        } else {
            return "" ;
        }
    }
    
    public function getNews() {
        
        return new \Answer\Widget\Misc\Section(
                \I18n::LAST_NEWS(),
                new \Quantyl\XML\Html\A("/Blog", \I18n::DETAILS()),
                null,
                new \Widget\Blog\PostList(\Model\Blog\Post::GetLasts(\Model\I18n\Lang::GetCurrent(), 2)),
                "card-2-3"
                ) ;
        
    }
    
    public function getMedia() {
        return new \Answer\Widget\Game\Screenshots("1-GAME", "screenshots card-1-3", new \Quantyl\XML\Html\A("/Screenshot", \I18n::DETAILS())) ;
    }
    
    
    public function getTplContent() {
        $res = "" ;
        $res .= $this->getAbout() ;
        $res .= $this->getNews() ;
        $res .= $this->getMedia() ;
        
        return $res ;
    }

    

}
