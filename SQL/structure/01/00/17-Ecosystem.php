<?php

class Build_17 {
    
    private static $insert_ecosystem ;
    private static $get_biome ;
    private static $get_item ;

    private static $_biome ;
    private static $_items ;
    
    public static function init() {
        
        self::$insert_ecosystem = Build::$pdo->prepare(
                "insert into game_ressource_ecosystem"
                . "    (`item`, `biome`, `min`, `max`)"
                . "  VALUES"
                . "    (:item,   :biome,  :min, :max)"
                ) ;

        self::$get_biome =  Build::$pdo->prepare(
                "select id from game_biome where `name` = :name"
                ) ;

        self::$get_item =  Build::$pdo->prepare(
                "select id from game_ressource_item where `name` = :name"
                ) ;
    }
    
    private static function _getBiome($name) {
        if (! isset(self::$_biome[$name])) {
            self::$get_biome->execute(array("name" => $name)) ;
            $res = self::$get_biome->fetch() ;
            self::$_biome[$name] = ($res === false ? null : $res["id"]) ;
        }
        return self::$_biome[$name] ;
    }
    
    private static function _getItem($name) {
        if (! isset(self::$_items[$name])) {
            self::$get_item->execute(array("name" => $name)) ;
            $res = self::$get_item->fetch() ;
            self::$_items[$name] = $res === false ? null : $res["id"] ;
        }
        return self::$_items[$name] ;

    }

    private static function _insert($statement, $params) {
        $statement->execute($params) ;
        return Build::$pdo->lastInsertId() ;
    }

    private static function seuil($value) {
        return min(10.0, max(0.0, $value)) ;
    }
    
    public static function Add(
            $biome_name,
            $item_name,
            $avg
            ) {

        
        $ecosystem = self::_insert(self::$insert_ecosystem, array (
            "item" => self::_getItem($item_name),
            "biome" => self::_getBiome($biome_name),
            "min" => self::seuil($avg - 2.0) / 10.0,
            "max" => self::seuil($avg + 2.0) / 10.0
        )) ;
    }
}

Build_17::init() ;


/* Désert */
Build_17::Add("desert_warm", "Kakto",     6.0) ;
Build_17::Add("desert_warm", "Aloe",      6.0) ;
Build_17::Add("desert_warm", "Bromelio",  6.0) ;
Build_17::Add("desert_warm", "Echevo",    6.0) ;
Build_17::Add("desert_warm", "Fangsorxo", 6.0) ;
Build_17::Add("desert_warm", "Squo",      6.0) ;

/* Steppe */
Build_17::Add("toundra_warm", "Aloe",    4.0) ;
Build_17::Add("toundra_warm", "Echevo",  4.0) ;
Build_17::Add("toundra_warm", "Lichoj",  4.0) ;
Build_17::Add("toundra_warm", "Arido",   6.0) ;
Build_17::Add("toundra_warm", "Beano",   6.0) ;
Build_17::Add("toundra_warm", "Gresbo", 10.0) ;
Build_17::Add("toundra_warm", "Avoro",   8.0) ;
Build_17::Add("toundra_warm", "Bailo",   6.0) ;

/* Savane */
Build_17::Add("grassland_warm", "Arido",   6.0) ;
Build_17::Add("grassland_warm", "Beano",   6.0) ;
Build_17::Add("grassland_warm", "Gresbo", 10.0) ;
Build_17::Add("grassland_warm", "Avoro",   6.0) ;
Build_17::Add("grassland_warm", "Ligio",   4.0) ;
Build_17::Add("grassland_warm", "Flento",  4.0) ;
Build_17::Add("grassland_warm", "Bailo",   6.0) ;
Build_17::Add("grassland_warm", "Rorro",   6.0) ;
Build_17::Add("grassland_warm", "Lavo",    4.0) ;
Build_17::Add("grassland_warm", "Bao",     8.0) ;

/* Brousse */
Build_17::Add("bush_warm", "Arido",   4.0) ;
Build_17::Add("bush_warm", "Beano",   4.0) ;
Build_17::Add("bush_warm", "Gresbo", 10.0) ;
Build_17::Add("bush_warm", "Avoro",   4.0) ;
Build_17::Add("bush_warm", "Ligio",   4.0) ;
Build_17::Add("bush_warm", "Flento",  2.0) ;
Build_17::Add("bush_warm", "Bailo",   8.0) ;
Build_17::Add("bush_warm", "Eiko",    8.0) ;
Build_17::Add("bush_warm", "Rorro",   8.0) ;
Build_17::Add("bush_warm", "Lavo",    8.0) ;
Build_17::Add("bush_warm", "Bao",     6.0) ;
Build_17::Add("bush_warm", "Oli",     6.0) ;

/* Jungle */
Build_17::Add("forest_warm", "Fangsorxo", 2.0) ;
Build_17::Add("forest_warm", "Squo",      2.0) ;
Build_17::Add("forest_warm", "Lichoj",    8.0) ;
Build_17::Add("forest_warm", "Somo",      8.0) ;
Build_17::Add("forest_warm", "Fiko",      8.0) ;
Build_17::Add("forest_warm", "Gresbo",    4.0) ;
Build_17::Add("forest_warm", "Avoro",     4.0) ;
Build_17::Add("forest_warm", "Ligio",    10.0) ;
Build_17::Add("forest_warm", "Flento",    4.0) ;
Build_17::Add("forest_warm", "Rorro",     4.0) ;
Build_17::Add("forest_warm", "Kver",      8.0) ;
Build_17::Add("forest_warm", "Pin",       4.0) ;
Build_17::Add("forest_warm", "Bao",      10.0) ;
Build_17::Add("forest_warm", "Oli",       8.0) ;

/* Banquise */
Build_17::Add("desert_cold", "Fangsorxo",  6.0) ;
Build_17::Add("desert_cold", "Lichoj",    10.0) ;
Build_17::Add("desert_cold", "Somo",       8.0) ;
Build_17::Add("desert_cold", "Fiko",       8.0) ;
Build_17::Add("desert_cold", "Gresbo",     2.0) ;
Build_17::Add("desert_cold", "Flento",     2.0) ;


/* Toundra */
Build_17::Add("toundra_cold", "Lichoj",   6.0) ;
Build_17::Add("toundra_cold", "Somo",     6.0) ;
Build_17::Add("toundra_cold", "Fiko",     6.0) ;
Build_17::Add("toundra_cold", "Jarkilo", 10.0) ;
Build_17::Add("toundra_cold", "Gresbo",   6.0) ;
Build_17::Add("toundra_cold", "Avoro",    4.0) ;
Build_17::Add("toundra_cold", "Flento",   2.0) ;
Build_17::Add("toundra_cold", "Eiko",     8.0) ;


/* Prairie */
Build_17::Add("grassland_cold", "Bromelio", 4.0) ;
Build_17::Add("grassland_cold", "Fiko",     6.0) ;
Build_17::Add("grassland_cold", "Jarkilo",  4.0) ;
Build_17::Add("grassland_cold", "Gresbo",  10.0) ;
Build_17::Add("grassland_cold", "Avoro",    6.0) ;
Build_17::Add("grassland_cold", "Flento",   4.0) ;
Build_17::Add("grassland_cold", "Bero",     6.0) ;
Build_17::Add("grassland_cold", "Thorno",   6.0) ;
Build_17::Add("grassland_cold", "Eiko",     6.0) ;
Build_17::Add("grassland_cold", "Salik",    8.0) ;


/* Maquis */
Build_17::Add("bush_cold", "Somo",    4.0) ;
Build_17::Add("bush_cold", "Fiko",    6.0) ;
Build_17::Add("bush_cold", "Jarkilo", 2.0) ;
Build_17::Add("bush_cold", "Gresbo",  4.0) ;
Build_17::Add("bush_cold", "Ligio",   6.0) ;
Build_17::Add("bush_cold", "Bero",    8.0) ;
Build_17::Add("bush_cold", "Bailo",   8.0) ;
Build_17::Add("bush_cold", "Thorno",  8.0) ;
Build_17::Add("bush_cold", "Eiko",    8.0) ;
Build_17::Add("bush_cold", "Bet",     6.0) ;
Build_17::Add("bush_cold", "Salik",   6.0) ;
Build_17::Add("bush_cold", "Kver",    6.0) ;


/* Forêt */
Build_17::Add("forest_cold", "Lichoj", 8.0) ;
Build_17::Add("forest_cold", "Somo",   8.0) ;
Build_17::Add("forest_cold", "Fiko",   8.0) ;
Build_17::Add("forest_cold", "Gresbo", 4.0) ;
Build_17::Add("forest_cold", "Ligio",  4.0) ;
Build_17::Add("forest_cold", "Bero",   4.0) ;
Build_17::Add("forest_cold", "Thorno", 4.0) ;
Build_17::Add("forest_cold", "Bet",    4.0) ;
Build_17::Add("forest_cold", "Salik",  4.0) ;
Build_17::Add("forest_cold", "Kver",   4.0) ;
Build_17::Add("forest_cold", "Spruc",  8.0) ;
Build_17::Add("forest_cold", "Larik",  8.0) ;
Build_17::Add("forest_cold", "Pin",    8.0) ;
Build_17::Add("forest_cold", "Abi",    8.0) ;
