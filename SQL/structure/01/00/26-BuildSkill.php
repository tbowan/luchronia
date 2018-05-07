<?php

class Build_26 {
    
    public static function init() {
        self::_initCharacteristics() ;
        self::initJobs() ;
        self::initTypes() ;
        self::initItems() ;
        self::initAdd() ;
    }

    private static function _get(&$table, $name, $statement) {
        if (! isset($table[$name])) {
            $statement->execute(array("name" => $name)) ;
            $row = $statement->fetch() ;
            $table[$name] = ($row === false ? null : $row["id"]) ;
        }
        return $table[$name] ;
    }
    
    private static function _insert($statement, $params) {
        try {
            $statement->execute($params) ;
            return Build::$pdo->lastInsertId() ;
        } catch (Exception $ex) {
            var_dump($statement) ;
            print_r($params) ;
            echo $ex ;
            exit() ;
        }
    }
    
    // Characteristics
    
    private static $_getCharacteristic ;
    private static $_theCharacteristics ;
    
    private static function _initCharacteristics() {
        self::$_theCharacteristics = array() ;
        
        self::$_getCharacteristic = Build::$pdo->prepare(
                "select id from game_characteristic where `name` = :name"
                ) ;
    }
    
    private static function _getCha($name) {
        return self::_get(self::$_theCharacteristics, $name, self::$_getCharacteristic) ;
    }
    
    // Jobs
    
    private static $_getJob  ;
    private static $_theJobs ;
    
    private static function initJobs() {
        self::$_theJobs = array() ;
        self::$_getJob = Build::$pdo->prepare("select * from game_building_job where `name` = :name"); 
    }
    
    public static function getJob($name) {
        return self::_get(self::$_theJobs, $name, self::$_getJob) ;
    }
    
    // Types
    
    private static $_theTypes ;
    private static $_getType ;
    
    private static function initTypes() {
        self::$_theTypes = array() ;
        self::$_getType = Build::$pdo->prepare("select * from game_building_type where `name` = :name"); 
        
    }
    
    public static function getType($name) {
        return self::_get(self::$_theTypes, $name, self::$_getType) ;
    }
    
    // Items
    
    private static $_getItem  ;
    private static $_theItems ;
    
    private static function initItems() {
        self::$_getItem = Build::$pdo->prepare(
                "select id from game_ressource_item where `name` = :name"
                ) ;
        
        self::$_theItems = array() ;
    }
    
    public static function getItem($name) {
        return self::_get(self::$_theItems, $name, self::$_getItem) ;
    }
    
    // Add Skills
    
    private static $_addSkill ;
    private static $_addTool  ;
        
    private static function initAdd() {
        self::$_addSkill = Build::$pdo->prepare(
                "insert into game_skill_skill"
                . " (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`)"
                . " VALUES"
                . " (:name , :classname , :building_job , :building_type , :by_hand , :characteristic )");
        
        self::$_addTool = Build::$pdo->prepare(
                "insert into game_skill_tool"
                . "    (`item`, `skill`, `coef`)"
                . "  VALUES"
                . "    (:item,   :skill,  :coef)"
                ) ;
    }
    
    public static function addSkill($type) {
        
        
        $skill = self::_insert(self::$_addSkill, array(
            "name"              => "Build$type",
            "classname"         => "Build",
            "building_job"      => self::getJob("Site"),
            "building_type"     => self::getType($type),
            "by_hand"           => 0.1,
            "characteristic"    => self::_getCha("Strength")
        )) ;
        
        self::_insert(self::$_addTool, array(
                "item" => self::getItem("Hammer"),
                "skill" => $skill,
                "coef" => 1.0
            )) ;
            
        return $skill ;
    }
    
}


Build_26::init() ;

Build_26::addSkill("Clay") ;
Build_26::addSkill("Timbered") ;
Build_26::addSkill("Wood") ;
Build_26::addSkill("Stone") ;
Build_26::addSkill("Brick") ;
Build_26::addSkill("Steel") ;
Build_26::addSkill("Glass") ;

