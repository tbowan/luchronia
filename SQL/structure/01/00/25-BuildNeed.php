<?php

class Build_25 {
    
    public static function init() {
        self::initTypes() ;
        self::initItems() ;
        self::initConstruction() ;
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
    
    // Building types
    
    private static $_theTypes ;
    
    private static function initTypes() {
        $st = Build::$pdo->prepare("select * from game_building_type"); 
        $st->execute() ;
        self::$_theTypes = array() ;
        
        foreach ($st as $row) {
            self::$_theTypes[$row["name"]] = $row["id"] ;
        }
    }
    
    public static function getType($name) {
        return self::$_theTypes[$name] ;
    }
    
    // Ressource Items
    
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
    
    // Building Construction
    
    private static $_addConstruction ;
    
    public static function initConstruction() {
        self::$_addConstruction = Build::$pdo->prepare(
                "insert into game_building_construction"
                . " (`item`, `type`, `amount`)"
                . " VALUES"
                . " (:item , :type , :amount )");
        
    }
    
    // Add construction
    
    
    public static function addConstruction($type, $item, $amount) {
        self::_insert(self::$_addConstruction, array(
            "item" => self::getItem($item),
            "type" => self::getType($type),
            "amount" => $amount
            )) ;
    }
    
    
}

Build_25::init() ;

// Clay (terre)
Build_25::AddConstruction("Clay", "Clay",  1.0) ;
Build_25::AddConstruction("Clay", "Plank", 0.1) ;

