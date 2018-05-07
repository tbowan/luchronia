<?php

class Build_21 {

    private static $_getItem  ;
    private static $_theItems ;
        
    private static $_getCharacteristic ;
    private static $_theCharacteristics ;
    
    private static $_getJob ;
    private static $_theJobs ;
    
    private static $_addSkill ;
    private static $_addFieldSkill    ;
    private static $_addTool  ;
    
    private static function _initItems() {
        self::$_getItem = Build::$pdo->prepare(
                "select id from game_ressource_item where `name` = :name"
                ) ;
        
        self::$_theItems = array() ;
    }
    
    private static function _initJobs() {
        self::$_theJobs = array() ;
        
        self::$_getJob =  Build::$pdo->prepare(
                "select id from game_building_job where `name` = :name"
                ) ;
    }
        
    private static function _initCharacteristics() {
        self::$_theCharacteristics = array() ;
        
        self::$_getCharacteristic = Build::$pdo->prepare(
                "select id from game_characteristic where `name` = :name"
                ) ;
    }
    
    private static function _initSkill() {
                
        self::$_addSkill = Build::$pdo->prepare(
                "insert into game_skill_skill"
                . " (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`)"
                . " VALUES"
                . " (:name,  :classname,  :building_job,  :building_type,  :by_hand,  :characteristic)") ;
        
        self::$_addTool = Build::$pdo->prepare(
                "insert into game_skill_tool"
                . "    (`item`, `skill`, `coef`)"
                . "  VALUES"
                . "    (:item,   :skill,  :coef)"
                ) ;
        
        self::$_addFieldSkill = Build::$pdo->prepare(
                "insert into game_skill_field"
                . "    (`skill`, `item`, `gain`)"
                . "  VALUES"
                . "    (:skill,  :item,  :gain )"
                ) ;
        
    }
    
    public static function init() {
        self::_initItems() ;
        self::_initJobs() ;
        self::_initCharacteristics() ;
        self::_initSkill() ;     
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
    
    private static function _get(&$table, $name, $statement) {
        if (! isset($table[$name])) {
            $statement->execute(array("name" => $name)) ;
            $row = $statement->fetch() ;
            $table[$name] = ($row === false ? null : $row["id"]) ;
        }
        return $table[$name] ;
    }
    
    public static function getItem($name) {
        return self::_get(self::$_theItems, $name, self::$_getItem) ;
    }
    
    private static function _getJob($name) {
        return self::_get(self::$_theJobs, $name, self::$_getJob) ;
    }
    
    private static function _getCha($name) {
        return self::_get(self::$_theCharacteristics, $name, self::$_getCharacteristic) ;
    }
    
    public static function addSkill($name, $hand, $item, $gain, $tools) {
        
        echo "    - Compétence : $name\n" ;
        
        $classname = "FieldGather" ;
        $field = self::_getJob("Field") ;
        $charac = self::_getCha("Perception") ;
        
        $skill = self::_insert(self::$_addSkill, array (
            "name" => $name,
            "classname" => $classname,
            "building_job" => $field,
            "building_type" => null,
            "by_hand" => $hand,
            "characteristic" => $charac
        )) ;
        
        self::_insert(self::$_addFieldSkill, array(
            "skill"     => $skill,
            "item"      => self::getItem($item),
            "gain"      => self::getItem($gain),
        )) ;
        
        foreach ($tools as $tool_name => $coef) {
            self::_insert(self::$_addTool, array(
                "item" => self::getItem($tool_name),
                "skill" => $skill,
                "coef" => $coef
            )) ;
        }
        
        return $skill ;
    }
    
}

Build_21::init() ;

/* 1 - Arbres - Wood Cutting*/

Build_21::addSkill("FieldCutBet",      null, "Bet",    "BetTimber",    array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutSalik",    null, "Salik",  "SalikTimber",  array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutKver",     null, "Kver",   "KverTimber",   array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutSpruc",    null, "Spruc",  "SprucTimber",  array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutLarik",    null, "Larik",  "LarikTimber",  array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutPin",      null, "Pin",    "PinTimber",    array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutAbi",      null, "Abi",    "AbiTimber",    array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutBao",      null, "Bao",    "BaoTimber",    array("Saw" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldCutOli",      null, "Oli",    "OliTimber",    array("Saw" => 0.2, "Axe" => 1.0)) ;

/* 2 - Arbres - Gathering */

Build_21::addSkill("FieldGatherPin",   null, "Pin",    "PinFruit",     array("Serpe" => 0.2, "Secateur" => 1.0)) ;
Build_21::addSkill("FieldGatherAbi",   null, "Abi",    "AbiFruit",     array("Serpe" => 0.2, "Secateur" => 1.0)) ;
Build_21::addSkill("FieldGatherBao",   null, "Bao",    "BaoFruit",     array("Serpe" => 0.2, "Secateur" => 1.0)) ;
Build_21::addSkill("FieldGatherOli",   null, "Oli",    "OliFruit",     array("Serpe" => 0.2, "Secateur" => 1.0)) ;

/* 3 - Herbes */

Build_21::addSkill("FieldGatherJarkilo", null, "Jarkilo", "JarkiloStem",    array("Serpe" => 0.1, "Secateur" => 0.2, "Scythe" => 1.0)) ;
Build_21::addSkill("FieldGatherGresbo",  null, "Gresbo",  "GresboFeed",     array("Serpe" => 0.1, "Secateur" => 0.2, "Scythe" => 1.0)) ;
Build_21::addSkill("FieldGatherAvoro",   null, "Avoro",   "AvoroStem",      array("Serpe" => 0.1, "Secateur" => 0.2, "Scythe" => 1.0)) ;
Build_21::addSkill("FieldGatherLigio",   null, "Ligio",   "LigioPlant",     array("Serpe" => 0.2, "Axe" => 1.0)) ;
Build_21::addSkill("FieldGatherFlento",  null, "Flento",  "FlentoFlower",   array("Serpe" => 0.2, "Axe" => 1.0)) ;

/* 4 - Cryptogames */

Build_21::addSkill("FieldGatherLichoj", null, "Lichoj", "LichojPlant",     array("Knife" => 0.5, "GathererKnife" => 1.0) ) ;
Build_21::addSkill("FieldGatherSomo",   null, "Somo",   "SomoMoss",        array("Knife" => 0.5, "GathererKnife" => 1.0) ) ;
Build_21::addSkill("FieldGatherFiko",   null, "Fiko",   "FikoPlant",       array("Knife" => 0.5, "GathererKnife" => 1.0) ) ;

/* 5 - Arbustes */
Build_21::addSkill("FieldGatherBero",   0.2,  "Bero",   "BeroFruit",       array("Glove" => 1.0, "Comb" => 2.0)) ;
Build_21::addSkill("FieldGatherThorno", 0.2,  "Thorno", "ThornoFruit",     array("Glove" => 1.0, "Comb" => 2.0)) ;
Build_21::addSkill("FieldGatherBailo",  null, "Bailo",  "BailoPlant",      array("Secateur" => 0.2, "SawOneHand" => 1.0)) ;
Build_21::addSkill("FieldGatherEiko",   null, "Eiko",   "EikoPlant",       array("Secateur" => 0.2, "SawOneHand" => 1.0)) ;
Build_21::addSkill("FieldGatherRorro",  null, "Rorro",  "RorroPlant",      array("Secateur" => 0.2, "SawOneHand" => 1.0)) ;
Build_21::addSkill("FieldGatherLavo",   null, "Lavo",   "LavoPlant",       array("Secateur" => 0.2, "SawOneHand" => 1.0)) ;

/* 6 - Legumineuse */
Build_21::addSkill("FieldGatherArido", 0.2, "Arido",   "AridoSeed",        array("Glove" => 1.0, "Comb" => 2.0) ) ;
Build_21::addSkill("FieldGatherBeano", 0.2, "Beano",   "BeanoHull",        array("Glove" => 1.0, "Comb" => 2.0) ) ;

/* 7 - plante grasses */

Build_21::addSkill("FieldGatherKakto",     null, "Kakto",     "KaktoPlant",        array("knife" => 0.5, "GathererKnife" => 1.0) ) ;
Build_21::addSkill("FieldGatherAloe",      null, "Aloe",      "AloePlant",         array("knife" => 0.5, "GathererKnife" => 1.0) ) ;
Build_21::addSkill("FieldGatherBromelio",  null, "Bromelio",  "BromelioPlant",     array("knife" => 0.5, "GathererKnife" => 1.0) ) ;
Build_21::addSkill("FieldGatherSquo",      null, "Squo",      "SquoPlant",         array("knife" => 0.5, "GathererKnife" => 1.0) ) ;
Build_21::addSkill("FieldGatherEchevo",    null, "Echevo",    "EchevoFlower",      array("Serpe" => 0.2,  "Secateur" => 1.0) ) ;
Build_21::addSkill("FieldGatherFangsorxo", null, "Fangsorxo", "FangsorxoFruit",    array("Serpe" => 0.2,  "Secateur" => 1.0) ) ;




