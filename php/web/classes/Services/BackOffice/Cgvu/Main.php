<?php

namespace Services\BackOffice\Cgvu ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Cgvu\Table() ;
    }
    
}

