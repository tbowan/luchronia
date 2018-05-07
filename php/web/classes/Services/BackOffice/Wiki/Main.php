<?php

namespace Services\BackOffice\wiki ;

use Model\Wiki\Page;
use Widget\BackOffice\Wiki\PageList;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        $pages = Page::getAllSorted() ;
        return new PageList($pages) ;
    }
}
