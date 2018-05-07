<?php

namespace Services\BackOffice\Ecom\Code ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Ecom\Bonus() ;
    }
    
}
