<?php

namespace Services\BackOffice\Config ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Config\Main() ;
    }
    
}
