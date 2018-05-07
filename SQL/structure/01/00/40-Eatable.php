<?php

class Build_040 {
    
    public static function init() {
        self::initItems() ;
        self::initEatable() ;
        self::initDrinkable() ;
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
    
    // Eatable
    
    private static $_addEatable ;
        
    private static function initEatable() {
        self::$_addEatable = Build::$pdo->prepare(
                "insert into game_ressource_eatable"
                . " (`item`, `energy`, `race`)"
                . " VALUES"
                . " (:item , :energy , :race )");
        
    }
    
    private static $_addDrinkable ;
    
    private static function initDrinkable() {
        self::$_addDrinkable = Build::$pdo->prepare(
                "insert into game_ressource_drinkable"
                . " (`item`, `energy`, `hydration`)"
                . " VALUES"
                . " (:item , :energy , :hydration )");
        
    }
    // Races
    
    public static $HUMAN    = 1 ;
    public static $CYBORG   = 2 ;
    public static $SELENITE = 3 ;
    public static $ALL      = null ;
    
    public static function addEatable($item, $race, $energy) {
        
        self::_insert(self::$_addEatable, array(
            "item"      => self::getItem($item),
            "energy"    => $energy,
            "race"      => $race
            
        )) ;
    }
    
    public static function addDrinkable($item, $energy, $hydration) {
        
        self::_insert(self::$_addDrinkable, array(
            "item"      => self::getItem($item),
            "energy"    => $energy,
            "hydration" => $hydration,
        )) ;
    }
    
}

Build_040::init() ;

Build_040::addDrinkable("Water",        0.0, 100.0) ;


Build_040::addDrinkable("BeroJuice",    22.0, 100.0) ;
Build_040::addDrinkable("BaoJuice",     22.0, 100.0) ;
Build_040::addDrinkable("AvoroJuice",   22.0, 100.0) ;
Build_040::addDrinkable("ThornoJuice",  22.0, 100.0) ;
Build_040::addDrinkable("KaktoJuice",   22.0, 100.0) ;
Build_040::addDrinkable("LigioJuice",   22.0, 100.0) ;
Build_040::addDrinkable("AloeJuice",    22.0, 100.0) ;


// Nourriture

// Fruits : 2 %
Build_040::addEatable("ThornoFruit",    Build_040::$HUMAN, 100.0) ;
Build_040::addEatable("BeroFruit",      Build_040::$HUMAN, 100.0) ;
Build_040::addEatable("FangsorxoFruit", Build_040::$HUMAN, 100.0) ;
Build_040::addEatable("KaktoPlant",     Build_040::$HUMAN, 100.0) ;

// Ingredients
Build_040::addEatable("Sugar",          Build_040::$HUMAN, 150.0) ;

// Muesli (4%)
Build_040::addEatable("ThornoMuesli",   Build_040::$HUMAN, 330.0) ;
Build_040::addEatable("BeroMuesli",     Build_040::$HUMAN, 330.0) ;
Build_040::addEatable("FangsorxoMuesli",Build_040::$HUMAN, 330.0) ;

// Soup (5.6 %)
Build_040::addDrinkable("RorroSoup",    265.0, 100.0) ;
Build_040::addDrinkable("FikoSoup",     265.0, 100.0) ;
Build_040::addDrinkable("LichojSoup",   265.0, 100.0) ;
Build_040::addDrinkable("SquoSoup",     265.0, 100.0) ;

// SMoothies (9.4 %)
Build_040::addEatable("ThornoSmoothie",     Build_040::$HUMAN, 95.0) ;
Build_040::addEatable("BeroSmoothie",       Build_040::$HUMAN, 95.0) ;
Build_040::addEatable("FangsorxoSmoothie",  Build_040::$HUMAN, 95.0) ;

// Milk
Build_040::addEatable("PinoMilk",       Build_040::$HUMAN, 100.0) ;
Build_040::addEatable("AridoMilk",      Build_040::$HUMAN, 100.0) ;
Build_040::addEatable("LigioMilk",      Build_040::$HUMAN, 100.0) ;

// CHeese
Build_040::addEatable("CheeseBasis",    Build_040::$HUMAN, 230.0) ;
Build_040::addEatable("FlentoCheese",   Build_040::$HUMAN, 60.0) ;
Build_040::addEatable("RorroCheese",    Build_040::$HUMAN, 60.0) ;

// Pain
Build_040::addEatable("AvoroBread",     Build_040::$HUMAN, 50.0) ;
Build_040::addEatable("LichojBread",    Build_040::$HUMAN, 50.0) ;

// Salades
Build_040::addEatable("FlentoSalad",    Build_040::$HUMAN, 155.0) ;
Build_040::addEatable("FikoSalad",      Build_040::$HUMAN, 155.0) ;
Build_040::addEatable("LichojSalad",    Build_040::$HUMAN, 155.0) ;
Build_040::addEatable("BeanoSalad",     Build_040::$HUMAN, 155.0) ;

// Roasted
Build_040::addEatable("AdanoRoasted",   Build_040::$HUMAN, 165.0) ;
Build_040::addEatable("PinoRoasted",    Build_040::$HUMAN, 165.0) ;
Build_040::addEatable("AridoRoasted",   Build_040::$HUMAN, 165.0) ;

// Cereal
Build_040::addEatable("ThornoCereal",       Build_040::$HUMAN, 610.0) ;
Build_040::addEatable("BeroCereal",         Build_040::$HUMAN, 610.0) ;
Build_040::addEatable("FangsorxoCereal",    Build_040::$HUMAN, 610.0) ;

// Candy
Build_040::addEatable("FlentoCandy",    Build_040::$HUMAN, 16.0) ;
Build_040::addEatable("BeroCandy",      Build_040::$HUMAN, 16.0) ;
Build_040::addEatable("LavoCandy",      Build_040::$HUMAN, 22.0) ;

// Confits
Build_040::addEatable("PinCandied",     Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("FlentoCandied",  Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("LavoCandied",    Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("EikoCandied",    Build_040::$HUMAN, 660.0) ;

// Confitures
Build_040::addEatable("ThornoJam",      Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("BeroJam",        Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("SquoJam",        Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("FangsorxoJam",   Build_040::$HUMAN, 660.0) ;
Build_040::addEatable("KaktoJam",       Build_040::$HUMAN, 660.0) ;

// Gateaux
Build_040::addEatable("PinoCake",       Build_040::$HUMAN, 80.0) ;
Build_040::addEatable("ThornoCake",     Build_040::$HUMAN, 80.0) ;
Build_040::addEatable("BeroCake",       Build_040::$HUMAN, 80.0) ;
Build_040::addEatable("SquoCake",       Build_040::$HUMAN, 80.0) ;
Build_040::addEatable("FangsorxoCake",  Build_040::$HUMAN, 80.0) ;
Build_040::addEatable("KaktoCake",      Build_040::$HUMAN, 80.0) ;

// Crepes
Build_040::addEatable("AvoroPancakes",              Build_040::$HUMAN, 840.0) ;
Build_040::addEatable("LichojPancakes",             Build_040::$HUMAN, 840.0) ;

Build_040::addEatable("PinoAvoroPancakes",          Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("FlentoAvoroPancakes",        Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("LavoAvoroPancakes",          Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("EikoAvoroPancakes",          Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("ThornoLichojPancakes",       Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("BeroLichojPancakes",         Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("SquoLichojPancakes",         Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("FangsorxoLichojPancakes",    Build_040::$HUMAN, 170.0) ;
Build_040::addEatable("KaktoLichojPancakes",        Build_040::$HUMAN, 170.0) ;

// Divers
Build_040::addEatable("AvoroPasta",     Build_040::$HUMAN, 100.0) ;
Build_040::addEatable("SquoGratin",     Build_040::$HUMAN, 240.0) ;
Build_040::addEatable("BeanoSteamed",   Build_040::$HUMAN, 240.0) ;

// Combustibles
Build_040::addEatable("Coal", Build_040::$CYBORG, 14.0) ;
Build_040::addEatable("Coke", Build_040::$CYBORG, 280.0) ;

// Cristaux
Build_040::addEatable("Sand",       Build_040::$SELENITE, 52.0) ;
Build_040::addEatable("IronOre",    Build_040::$SELENITE, 60.0) ;
