<?php

namespace Services\Hof ;

class Main extends \Quantyl\Service\EnhancedService {
    
    public function getView() {
        return new \Widget\Hof\Main() ;
    }
    
}
