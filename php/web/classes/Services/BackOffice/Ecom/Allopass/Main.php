<?php

namespace Services\BackOffice\Ecom\Allopass ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Ecom\Allopass() ;
    }
    
}
