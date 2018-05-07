<?php

namespace Quantyl\Dao ;

/**
 * Wrapper autour de PDO pour ajouter ce qu'on aimerait bien
 */

use \PDO ;
use \Quantyl\Configuration\Configuration ;

class Dal {

    private static $_pdo ;
    
    private static $_nsReplace ;
    private static $_nsBase ;
    
    public static function getPdo() {
        if (isset(self::$_pdo)) {
            return self::$_pdo ;
        } else {
            // TODO better exception
            throw new \Exception() ;
        }
    }
    
    public static function classToTable($classname) {
        $withoutBase      = str_replace(self::$_nsBase . "\\", "", $classname) ;
        $lower            = strtolower($withoutBase) ;
        return              str_replace("\\", self::$_nsReplace, $lower) ;
    }
    
    public static function tableToClass($tablename) {
        $parts     = explode(self::$_nsReplace, $tablename) ;
        $nparts    = array_map("ucfirst", $parts) ;
        $classname = implode("\\", $nparts) ;
        return "\\" . self::$_nsBase . "\\" . $classname ;
    }
    
    public static function initialize(Configuration $cfg) {
        self::createPDO(
                $cfg["dao.driver"],
                $cfg["dao.database"],
                $cfg["dao.hostname"],
                $cfg["dao.username"],
                $cfg["dao.password"]
                ) ;
        
        self::$_nsBase    = $cfg["dao.nsBase"] ;
        self::$_nsReplace = $cfg["dao.nsReplace"] ;

    }
    
    private static function createPDO($driver, $database, $hostname, $username, $password) {
        self::$_pdo = new PDO(
                "$driver:dbname=$database;host=$hostname;charset=utf8",
                $username,
                $password
            );
        self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    
    
}
