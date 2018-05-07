<?php

namespace Model\Ecom\Allopass;

class Product extends \Quantyl\Dao\BddObject {

    public static function GetFromIdd($idd) {
        return self::getSingleResult(""
                . " select * from `" . self::getTableName() . "`"
                . " where idd = :idd",
                array("idd" => $idd)) ;
    }
    
}
