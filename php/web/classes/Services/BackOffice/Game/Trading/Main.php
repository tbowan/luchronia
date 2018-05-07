<?php

namespace Services\BackOffice\Game\Trading ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Game\Tradings() ;
    }
    
}
