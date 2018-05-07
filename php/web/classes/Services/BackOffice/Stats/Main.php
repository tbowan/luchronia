<?php

namespace Services\BackOffice\Stats ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Answer\View\BackOffice\Stats($this) ;
    }
    
}
