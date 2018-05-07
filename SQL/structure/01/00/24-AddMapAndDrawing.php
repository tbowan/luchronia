<?php

class Build_24 {
    
    public static function init() {
        self::_initCharacteristics() ;
        self::initJobs() ;
        self::initLevelJob() ;
        self::initTypes() ;
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
    
    private static $_theJobs ;
    
    private static function initJobs() {
        $st = Build::$pdo->prepare("select * from game_building_job"); 
        $st->execute() ;
        self::$_theJobs = array() ;
        
        foreach ($st as $row) {
            self::$_theJobs[$row["name"]] = $row ;
        }
    }
    
    public static function getJob($name) {
        return self::$_theJobs[$name] ;
    }
    
    // Types
    
    private static $_theTypes ;
    
    private static function initTypes() {
        $st = Build::$pdo->prepare("select * from game_building_type"); 
        $st->execute() ;
        self::$_theTypes = array() ;
        
        foreach ($st as $row) {
            self::$_theTypes[$row["name"]] = $row ;
        }
    }
    
    public static function getType($name) {
        return self::$_theTypes[$name] ;
    }
    
    // Set level for building job
    
    private static $_setLevelJob ;
    
    private static function initLevelJob() {
        self::$_setLevelJob = Build::$pdo->prepare(
                "update game_building_job"
                . " set `level` = :level"
                . " where `name` = :name");
        
    }
    
    public static function setLevel($job, $level) {
        self::$_setLevelJob->execute(array(
            "name" => $job,
            "level" => $level
        )) ;
        self::$_theJobs[$job]["level"] = $level ;
    }
    
    public static function setLelels($table) {
        foreach ($table as $name => $level) {
            self::setLevel($name, $level) ;
        }
    }
    
    // Set maps and drawing skills
    
    private static $_addSkill ;
    private static $_addItem ;
    private static $_addMap ;
    
    private static function initAdd() {
        self::$_addSkill = Build::$pdo->prepare(
                "insert into game_skill_skill"
                . " (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`)"
                . " VALUES"
                . " (:name , :classname , :building_job , :building_type , :by_hand , :characteristic )");
        self::$_addItem = Build::$pdo->prepare(
                "insert into game_ressource_item"
                . " (`name`, `groupable`)"
                . " VALUES"
                . " (:name , :groupable )");
        self::$_addMap = Build::$pdo->prepare(
                "insert into game_building_map"
                . " (`item`, `job`, `type`, `level`, `tech`, `skill`)"
                . " VALUES"
                . " (:item , :job , :type , :level , :tech , :skill )");
    }
    
    private static function addSkill($job, $type) {
        $jobname  = $job["name"] ;
        $typename = $type["name"] ;
        
        $architect =  self::getJob("Architect") ;
        
        $skill = self::_insert(self::$_addSkill, array(
            "name"              => "DrawMap{$jobname}{$typename}",
            "classname"         => "DrawMap",
            "building_job"      => $architect["id"],
            "building_type"     => null,
            "by_hand"           => 1.0,
            "characteristic"    => self::_getCha("Mental")
        )) ;
            
        return $skill ;
    }
    
    private static function addMap($job, $type, $level, $skill) {
        $jobname  = $job["name"] ;
        $typename = $type["name"] ;
        
        // Add Item
        $item = self::_insert(self::$_addItem, array(
            "name"      => "Map{$jobname}{$typename}$level",
            "groupable" => 1
        )) ;
            
        // Add The Map
        $hpc = $level * ($level + 1) / 2 ;
        $map = self::_insert(self::$_addMap, array(
            "item"  => $item,
            "job"   => $job["id"],
            "type"  => $type["id"],
            "level" => $level,
            "tech"  => $job["technology"] + $hpc * $job["health"] * $type["technology"],
            "skill" => $skill,
        )) ;
        
        return $map ;
    }
    
    public static function setMapAndDrawing() {
        foreach (self::$_theJobs as $name => $job) {
            foreach (self::$_theTypes as $type) {
                $skill = self::addSkill($job, $type) ;
                for ($level = 1; $level <= $job["level"]; $level++) {
                    self::addMap($job, $type, $level, $skill) ;
                }
            }
        }
    }
}


Build_24::init() ;

echo "      - Set max level for buildings\n" ;

Build_24::setLelels(array(
    // Administratif
    "TownHall"      => 5,
    "Prefecture"    => 5,
    "Palace"        => 5,
    "TradingPost"   => 5,
    // Secteur primaire
    "Woodcutter"    => 5,
    "Gatherer"      => 5,
    "Quary"         => 5,
    "Mine"          => 5,
    "Well"          => 5,
    "Field"         => 5,
    "Storehouse"    => 5,
    // Secteur secondaire
    "Druid"         => 5,
    "Kitchen"       => 5,
    "Sawmill"       => 3,
    "Coal"          => 1,
    "Cokery"        => 1,
    "LowFurnace"    => 1,
    "BlastFurnace"  => 1,
    "Forge"         => 5,
    "Drying"        => 1,
    "Basketry"      => 2,
    "Mill"          => 1,
    "Glassware"     => 1,
    "Brickyard"     => 1,
    "Weaver"        => 1,
    "Brewery"       => 1,
    // Secteur tertiaire
    "Market"        => 1,
    "Exchange"      => 1,
    "Tavern"        => 1,
    "Forum"         => 1,
    "Post"          => 1,
    "Hospital"      => 1,
    // Secteur quaternaire
    "Architect"     => 5,
    "Excavation"    => 5,
    "Library"       => 5,
    "Academy"       => 5,
    "Spaceport"     => 5,
    // Secteur militaire
    "Wall"          => 5,
)) ;

echo "      - Create Maps and Drawing skills\n" ;

Build_24::setMapAndDrawing() ;

echo "      - Done\n" ;