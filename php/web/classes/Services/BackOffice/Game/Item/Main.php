<?php

namespace Services\BackOffice\Game\Item ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new \Widget\BackOffice\Game\Item() ;
    }
    
}
