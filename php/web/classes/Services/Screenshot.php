<?php

namespace Services ;

class Screenshot extends \Quantyl\Service\EnhancedService {
    
    public function getView() {
        return new \Answer\Widget\Game\Screenshots() ;
    }
    
}
