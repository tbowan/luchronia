<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Group;
use Widget\BackOffice\Group\GroupList;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        return new GroupList(Group::GetAll()) ;
    }
    
}
