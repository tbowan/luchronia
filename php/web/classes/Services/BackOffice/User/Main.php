<?php

namespace Services\BackOffice\User ;

use Widget\BackOffice\User\UserList;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new UserList($this->getUser()) ;
    }
    
}
