<?php

namespace Model\Identity ;

class Group extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public static function getNameField() {
        return "name" ;
    }

}
