<?php

namespace Answer\Widget\Wiki ;

class Page extends \Quantyl\Answer\Widget {
    
    private $_page ;
    
    public function __construct(\Model\Wiki\Page $page) {
        parent::__construct();
        $this->_page = $page ;
    }
    
    public function getContent() {
        return $this->_page->content ;
    }
    
}
