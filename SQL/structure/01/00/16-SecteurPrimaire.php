<?php

class Build_16 {
    
    private static $insert_skill ;
    private static $insert_tool ;
    private static $insert_primary ;
    private static $get_job ;
    private static $get_characteristic ;
    private static $get_item ;
    private static $characteristics ;
    
    public static function init() {
        
        self::$insert_skill = Build::$pdo->prepare(
                "insert into game_skill_skill"
                . "    (`name`, `classname`, `by_hand`, `building_job`, `building_type`, `characteristic` )"
                . "  VALUES"
                . "    (:name,   :classname,  :by_hand, :building_job,  :buiding_type,   :cha)"
                ) ;

        self::$insert_tool = Build::$pdo->prepare(
                "insert into game_skill_tool"
                . "    (`item`, `skill`, `coef`)"
                . "  VALUES"
                . "    (:item,   :skill,  :coef)"
                ) ;

        self::$insert_primary = Build::$pdo->prepare(
                "insert into game_skill_primary"
                . "    (`skill`, `in`, `out`)"
                . "  VALUES"
                . "    (:skill,  :in,  :out)"
                ) ;

        self::$get_job =  Build::$pdo->prepare(
                "select id from game_building_job where `name` = :name"
                ) ;

        self::$get_item =  Build::$pdo->prepare(
                "select id from game_ressource_item where `name` = :name"
                ) ;
        
        self::$get_characteristic = Build::$pdo->prepare(
                "select id from game_characteristic where `name` = :name"
                ) ;
        
        self::$characteristics = array() ;
    }
    
    private static function _getJob($name) {
        self::$get_job->execute(array("name" => $name)) ;
        $res = self::$get_job->fetch() ;
        return $res === false ? null : $res["id"] ;
    }
    
    private static function _getItem($name) {
        self::$get_item->execute(array("name" => $name)) ;
        $res = self::$get_item->fetch() ;
        return $res === false ? null : $res["id"] ;
    }

    private static function _getCha($name) {
        if (! isset(self::$characteristics[$name])) {
            self::$get_characteristic->execute(array("name" => $name)) ;
            $res = self::$get_characteristic->fetch() ;
            self::$characteristics[$name] = ($res === false ? null : $res["id"]) ;

        }
        return self::$characteristics[$name] ;
    }

    private static function _insert($statement, $params) {
        $statement->execute($params) ;
        return Build::$pdo->lastInsertId() ;
    }

    public static function Add(
            $name,
            $cha,
            $classname,
            $building_job,
            $byhand,
            $tools,
            $in,
            $out
            ) {

        $skill = self::_insert(self::$insert_skill, array (
            "name" => $name,
            "classname" => $classname,
            "by_hand" => $byhand,
            "building_job" => self::_getJob($building_job),
            "buiding_type" => null,
            "cha" => static::_getCha($cha)
        )) ;

        foreach ($tools as $tool => $coef) {
            self::_insert(self::$insert_tool, array (
                "item" => self::_getItem($tool),
                "skill" => $skill,
                "coef" => $coef
            )) ;
        }

        self::_insert(self::$insert_primary, array (
            "skill" => $skill,
            "in"    => self::_getItem($in),
            "out"   => self::_getItem($out),
        )) ;
    }
}

Build_16::init() ;

/* Bucheron */
Build_16::Add("CutBet",   "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Bet",   "BetTimber"   ) ;
Build_16::Add("CutSalik", "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Salik", "SalikTimber" ) ;
Build_16::Add("CutKver",  "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Kver",  "KverTimber"  ) ;
Build_16::Add("CutSpruc", "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Spruc", "SprucTimber" ) ;
Build_16::Add("CutLarik", "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Larik", "LarikTimber" ) ;
Build_16::Add("CutPin",   "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Pin",   "PinTimber"   ) ;
Build_16::Add("CutAbi",   "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Abi",   "AbiTimber"   ) ;
Build_16::Add("CutBao",   "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Bao",   "BaoTimber"   ) ;
Build_16::Add("CutOli",   "Strength", "Primary", "Woodcutter", null, array("Saw" => 1.0, "Axe" => 2.0), "Oli",   "OliTimber"   ) ;

/* Gatherer  : Tree */
Build_16::Add("GatherPin", "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 2.0), "Pin",   "PinFruit" ) ;
Build_16::Add("GatherAbi", "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 2.0), "Abi",   "AbiFruit" ) ;
Build_16::Add("GatherBao", "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 2.0), "Bao",   "BaoFruit" ) ;
Build_16::Add("GatherOli", "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 2.0), "Oli",   "OliFruit" ) ;

/* Gatherer : Grass */
Build_16::Add("GatherJarkilo", "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 1.5, "Scythe" => 2.0), "Jarkilo", "JarkiloStem" ) ;
Build_16::Add("GatherGresbo",  "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 1.5, "Scythe" => 2.0), "Gresbo",  "GresboFeed" ) ;
Build_16::Add("GatherAvoro",   "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Secateur" => 1.5, "Scythe" => 2.0), "Avoro",   "AvoroStem" ) ;
Build_16::Add("GatherLigio",   "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Axe" => 2.0),                       "Ligio",   "LigioPlant" ) ;
Build_16::Add("GatherFlento",  "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0, "Axe" => 2.0),                       "Flento",  "FlentoFlower" ) ;

/* Gather : cryptogames */
Build_16::Add("GatherLichoj", "Perception", "Primary", "Gatherer", null, array("Knife" => 1.0, "GathererKnife" => 2.0), "Lichoj", "LichojPlant" ) ;
Build_16::Add("GatherSomo",   "Perception", "Primary", "Gatherer", null, array("Knife" => 1.0, "GathererKnife" => 2.0), "Somo",   "SomoMoss" ) ;
Build_16::Add("GatherFiko",   "Perception", "Primary", "Gatherer", null, array("Knife" => 1.0, "GathererKnife" => 2.0), "Fiko",   "FikoPlant" ) ;

/* Gather : Arbustes */
Build_16::Add("GatherBero",   "Perception", "Primary", "Gatherer", 1.0,  array("Glove" => 2.0, "Comb" => 2.0),          "Bero",   "BeroFruit" ) ;
Build_16::Add("GatherThorno", "Perception", "Primary", "Gatherer", 1.0,  array("Glove" => 2.0, "Comb" => 2.0),          "Thorno", "ThornoFruit" ) ;
Build_16::Add("GatherBailo",  "Perception", "Primary", "Gatherer", null, array("Secateur" => 1.0, "SawOneHand" => 2.0), "Bailo",  "BailoPlant" ) ;
Build_16::Add("GatherEiko",   "Perception", "Primary", "Gatherer", null, array("Secateur" => 1.0, "SawOneHand" => 2.0), "Eiko",   "EikoPlant" ) ;
Build_16::Add("GatherRorro",  "Perception", "Primary", "Gatherer", null, array("Secateur" => 1.0, "SawOneHand" => 2.0), "Rorro",  "RorroPlant" ) ;
Build_16::Add("GatherLavo",   "Perception", "Primary", "Gatherer", null, array("Secateur" => 1.0, "SawOneHand" => 2.0), "Lavo",   "LavoPlant" ) ;

/* Gather : Legumineuse */
Build_16::Add("GatherArido", "Perception", "Primary", "Gatherer", 1.0,  array("Glove" => 2.0, "Comb" => 2.0), "Arido",   "AridoSeed" ) ;
Build_16::Add("GatherBeano", "Perception", "Primary", "Gatherer", 1.0,  array("Glove" => 2.0, "Comb" => 2.0), "Beano",   "BeanoHull" ) ;

/* Gather : plante grasses */
Build_16::Add("GatherKakto",     "Perception", "Primary", "Gatherer", null, array("knife" => 1.0, "GathererKnife" => 2.0), "Kakto",     "KaktoPlant" ) ;
Build_16::Add("GatherAloe",      "Perception", "Primary", "Gatherer", null, array("knife" => 1.0, "GathererKnife" => 2.0), "Aloe",      "AloePlant" ) ;
Build_16::Add("GatherBromelio",  "Perception", "Primary", "Gatherer", null, array("knife" => 1.0, "GathererKnife" => 2.0), "Bromelio",  "BromelioPlant" ) ;
Build_16::Add("GatherSquo",      "Perception", "Primary", "Gatherer", null, array("knife" => 1.0, "GathererKnife" => 2.0), "Squo",      "SquoPlant" ) ;
Build_16::Add("GatherEchevo",    "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0,  "Secateur" => 2.0),     "Echevo",    "EchevoFlower" ) ;
Build_16::Add("GatherFangsorxo", "Perception", "Primary", "Gatherer", null, array("Serpe" => 1.0,  "Secateur" => 2.0),     "Fangsorxo", "FangsorxoFruit" ) ;

/* Quary */
Build_16::Add("QuarySand",      "Strength", "Primary", "Quary", null, array("Shovel" => 2.0),                      "Sand",      "Sand" ) ;
Build_16::Add("QuaryClay",      "Strength", "Primary", "Quary", null, array("Shovel" => 2.0),                      "Clay",      "Clay" ) ;
Build_16::Add("QuaryLimeStone", "Strength", "Primary", "Quary", null, array("Pickaxe" => 2.0, "Dynamite" => 10.0), "LimeStone", "LimeStone" ) ;
            
/* Mine */
Build_16::Add("MineCoal",    "Strength", "Primary", "Mine", null, array("Pickaxe" => 2.0, "Dynamite" => 10.0), "Coal",    "Coal" ) ;
Build_16::Add("MineIronOre", "Strength", "Primary", "Mine", null, array("Pickaxe" => 2.0, "Dynamite" => 10.0), "IronOre", "IronOre" ) ;

# 9 - Well
Build_16::Add("WellWater",   "Strength", "Primary", "Well", null, array("SealLiquid" => 5.0, "Gourd" => 10.0), "Water",   "Water" ) ;
