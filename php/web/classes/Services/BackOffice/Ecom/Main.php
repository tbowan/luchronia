<?php

namespace Services\BackOffice\Ecom ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Ecom\Main() ;
    }
    
}
