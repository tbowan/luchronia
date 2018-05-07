<?php

namespace Services\BackOffice\Game\Delivery ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Game\Delivery() ;
    }
    
}
