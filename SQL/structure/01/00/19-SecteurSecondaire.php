<?php

class Build_19 {

    private static $_addItem;
    private static $_getItem;
    private static $_theItems;
    private static $_getCharacteristic;
    private static $_theCharacteristics;
    private static $_getJob;
    private static $_theJobs;
    private static $_addSkill;
    private static $_addIn;
    private static $_addOut;
    private static $_addTool;

    private static function _initItems() {
        self::$_addItem = Build::$pdo->prepare(
                "insert into game_ressource_item"
                . " (`name`, `groupable`)"
                . " VALUES"
                . " (:name,  :groupable)");

        self::$_getItem = Build::$pdo->prepare(
                "select id from game_ressource_item where `name` = :name"
                );

        self::$_theItems = array();
    }

    // Slots

    private static $_theSlots;
    private static $_getSlot;
    private static $_insert_equipable;

    public static function _initSlots() {

        self::$_theSlots = array();

        self::$_getSlot = Build::$pdo->prepare(
                "select id from game_ressource_slot where `name` = :name"
                );

        self::$_insert_equipable = Build::$pdo->prepare(
                "insert into game_ressource_equipable"
                . "    (`item`, `slot`, `amount`, `race`, `sex`)"
                . "  VALUES"
                . "    (:item,  :slot,  :amount,  :race,  :sex )"
                );
    }

    public static function getSlot($name) {
        return self::_get(self::$_theSlots, $name, self::$_getSlot);
    }

    // Characteristics 

    private static function _initCharacteristics() {
        self::$_theCharacteristics = array();

        self::$_getCharacteristic = Build::$pdo->prepare(
                "select id from game_characteristic where `name` = :name"
                );
    }

    private static function _initJobs() {
        self::$_theJobs = array();

        self::$_getJob = Build::$pdo->prepare(
                "select id from game_building_job where `name` = :name"
                );
    }

    private static function _initSkill() {

        self::$_addSkill = Build::$pdo->prepare(
                "insert into game_skill_skill"
                . " (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`)"
                . " VALUES"
                . " (:name,  :classname,  :building_job,  :building_type,  :by_hand,  :characteristic)");

        self::$_addTool = Build::$pdo->prepare(
                "insert into game_skill_tool"
                . "    (`item`, `skill`, `coef`)"
                . "  VALUES"
                . "    (:item,   :skill,  :coef)"
                );

        self::$_addIn = Build::$pdo->prepare(
                "insert into game_skill_in"
                . "    (`item`, `skill`, `amount`)"
                . "  VALUES"
                . "    (:item,   :skill,  :amount)"
                );

        self::$_addOut = Build::$pdo->prepare(
                "insert into game_skill_out"
                . "    (`item`, `skill`, `amount`)"
                . "  VALUES"
                . "    (:item,   :skill,  :amount)"
                );
    }

    public static function init() {
        self::_initItems();
        self::_initCharacteristics();
        self::_initJobs();
        self::_initSlots();
        self::_initSkill();
    }

    private static function _insert($statement, $params) {
        try {
            $statement->execute($params);
            return Build::$pdo->lastInsertId();
        } catch (Exception $ex) {
            var_dump($statement);
            print_r($params);
            echo $ex;
            exit();
        }
    }

    public static function addItem($name, $groupable) {
        // exists ?
        $prev = self::getItem($name);
        if ($prev === null) {
            $id = self::_insert(
                            self::$_addItem, array("name" => $name, "groupable" => $groupable)
                    );
            self::$_theItems[$name] = $id;
            return $id;
        } else {
            return $prev;
        }
    }

    private static function _get(&$table, $name, $statement) {
        if (!isset($table[$name])) {
            $statement->execute(array("name" => $name));
            $row = $statement->fetch();
            $table[$name] = ($row === false ? null : $row["id"]);
        }
        return $table[$name];
    }

    public static function getItem($name) {
        return self::_get(self::$_theItems, $name, self::$_getItem);
    }

    private static function _getCha($name) {
        return self::_get(self::$_theCharacteristics, $name, self::$_getCharacteristic);
    }

    private static function _getJob($name) {
        return self::_get(self::$_theJobs, $name, self::$_getJob);
    }

    public static function addSkill($name, $charac, $job, $hand, $ins, $outs, $tools) {

        echo "    - Compétence : $name\n";

        $classname = "Secondary";

        $skill = self::_insert(self::$_addSkill, array(
                    "name" => $name,
                    "classname" => $classname,
                    "building_job" => self::_getJob($job),
                    "building_type" => null,
                    "by_hand" => $hand,
                    "characteristic" => self::_getCha($charac)
                ));

        foreach ($tools as $tool_name => $coef) {
            self::_insert(self::$_addTool, array(
                "item" => self::getItem($tool_name),
                "skill" => $skill,
                "coef" => $coef
            ));
        }

        foreach ($ins as $item_name => $amount) {
            self::_insert(self::$_addIn, array(
                "item" => self::getItem($item_name),
                "skill" => $skill,
                "amount" => $amount
            ));
        }

        foreach ($outs as $item_name => $amount) {
            self::_insert(self::$_addOut, array(
                "item" => self::getItem($item_name),
                "skill" => $skill,
                "amount" => $amount
            ));
        }

        return $skill;
    }

    public static function AddEquipable($item, $slot, $amount) {
        return self::_insert(self::$_insert_equipable, array(
                    "item" => self::getItem($item),
                    "slot" => self::getSlot($slot),
                    "amount" => $amount,
                    "race" => null,
                    "sex" => null
                ));
    }

}

Build_19::init();

// 1. Cabane de druide
// 1.1. Bases
// 1.1.1. Fat Basis
Build_19::addItem("OliButter", true); // Beurre d'oli
Build_19::addItem("AridoButter", true); // Beurre d'arido
Build_19::addItem("AloeGel", true); // Gel d'aloe
Build_19::addItem("ThornoButter", true); // Pectine
Build_19::addItem("FatBasis", true); // Base graisseuse

Build_19::addItem("MortarAndPestle", false); // Mortier et Pilon
Build_19::AddEquipable("MortarAndPestle", "Hand", 1.0);

Build_19::addSkill("MakeFatBasisOli", "Perception", "Druid", null, array("OliButter" => 1.0), array("FatBasis" => 1.0), array("MortarAndPestle" => 1.0));
Build_19::addSkill("MakeFatBasisArido", "Perception", "Druid", null, array("AridoButter" => 1.0), array("FatBasis" => 1.0), array("MortarAndPestle" => 1.0));
Build_19::addSkill("MakeFatBasisAloe", "Perception", "Druid", null, array("AloeGel" => 1.0), array("FatBasis" => 1.0), array("MortarAndPestle" => 1.0));
Build_19::addSkill("MakeFatBasisThorno", "Perception", "Druid", null, array("ThornoButter" => 1.0), array("FatBasis" => 1.0), array("MortarAndPestle" => 1.0));

// 1.1.2. Sirup & potion

Build_19::addItem("Sugar", true); // Sucre
Build_19::addItem("BeroJuice", true); // Jus d'airelles
Build_19::addItem("BaoJuice", true); // Jus d'Adano
Build_19::addItem("AvoroJuice", true); // Jus d'Avoro
Build_19::addItem("ThornoJuice", true); // Jus de Thorno
Build_19::addItem("KaktoJuice", true); // Jus de Thorno
Build_19::addItem("SugarBasis", true); // Base sucrée

Build_19::addSkill("MakeSugarBasisSugar", "Perception", "Druid", null, array("Sugar" => 1.0, "Water" => 1.0), array("SugarBasis" => 1.0), array("MortarAndPestle" => 1.0));
Build_19::addSkill("MakeSugarBasisBero", "Perception", "Druid", 1.0, array("BeroJuice" => 1.0), array("SugarBasis" => 1.0), array());
Build_19::addSkill("MakeSugarBasisBao", "Perception", "Druid", 1.0, array("BaoJuice" => 1.0), array("SugarBasis" => 1.0), array());
Build_19::addSkill("MakeSugarBasisAvoro", "Perception", "Druid", 1.0, array("AvoroJuice" => 1.0), array("SugarBasis" => 1.0), array());
Build_19::addSkill("MakeSugarBasisThorno", "Perception", "Druid", 1.0, array("ThornoJuice" => 1.0), array("SugarBasis" => 1.0), array());
Build_19::addSkill("MakeSugarBasisKakto", "Perception", "Druid", 1.0, array("KaktoJuice" => 1.0), array("SugarBasis" => 1.0), array());

// 1.1.3. Pills
Build_19::addItem("PillBasis", true); // Base sucrée
Build_19::addSkill("MakePillBasisSugar", "Perception", "Druid", null, array("Sugar" => 1.0), array("PillBasis" => 1.0), array("MortarAndPestle" => 1.0));

// 1.2. Balms (baumes)
Build_19::addItem("BasketeryPot", true); // Petit pot en vanerie
Build_19::addItem("WoodPot", true); // Petit pot en bois
Build_19::addItem("GlassPot", true); // Petit pot en verre
Build_19::AddItem("Tube", true); // Tube (de dentifrice ou autre)

Build_19::addItem("LigioJuice", true); // Suc (jus) de Ligio
Build_19::addItem("AloeJuice", true); // Suc d'Aloe
// Baume de récupération
Build_19::addItem("BalmRecovery", true);
Build_19::addSkill("MakeBalmRecovery", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "BasketeryPot" => 1.0,
    "EikoPlant" => 1.0,
    "ThornoFruit" => 1.0
        ), array("BalmRecovery" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume réconfortant
Build_19::addItem("BalmComforting", true);
Build_19::addSkill("MakeBalmComforting", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "BasketeryPot" => 1.0,
    "AbiFruit" => 1.0,
    "LavoPlant" => 1.0,
        ), array("BalmComforting" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume d'aura améliorée
Build_19::addItem("BalmAura", true);
Build_19::addSkill("MakeBalmAura", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "BasketeryPot" => 1.0,
    "EikoPlant" => 1.0,
    "LavoPlant" => 1.0,
        ), array("BalmAura" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume d'amélioration physique
Build_19::addItem("BalmPhysic", true);
Build_19::addSkill("MakeBalmPhysic", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "WoodPot" => 1.0,
    "AbiFruit" => 1.0,
    "LichojPlant" => 1.0,
        ), array("BalmPhysic" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume de beauté
Build_19::addItem("BalmCharisma", true);
Build_19::addSkill("MakeBalmCharisma", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "WoodPot" => 1.0,
    "LavoPlant" => 1.0,
    "EikoPlant" => 1.0,
    "LichojPlant" => 1.0,
        ), array("BalmCharisma" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume naturel
Build_19::addItem("BalmNatural", true);
Build_19::addSkill("MakeBalmNatural", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "Tube" => 1.0,
    "AbiFruit" => 1.0,
    "FlentoFlower" => 1.0,
    "LigioJuice" => 1.0,
    "LichojPlant" => 1.0,
        ), array("BalmNatural" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume d'intellection
Build_19::addItem("BalmIntellection", true);
Build_19::addSkill("MakeBalmIntellection", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "Tube" => 1.0,
    "AloeJuice" => 1.0,
    "PinFruit" => 1.0,
    "LigioJuice" => 1.0,
    "EikoPlant" => 1.0,
    "BailoPlant" => 1.0,
        ), array("BalmIntellection" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume de perfection
Build_19::addItem("BalmPerfection", true);
Build_19::addSkill("MakeBalmPerfection", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "Tube" => 1.0,
    "AloeJuice" => 1.0,
    "PinFruit" => 1.0,
    "FlentoFlower" => 1.0,
    "ThornoFruit" => 1.0,
    "BailoPlant" => 1.0,
    "LichojPlant" => 1.0,
        ), array("BalmPerfection" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume de discrétion
Build_19::addItem("BalmDiscretion", true);
Build_19::addSkill("MakeBalmDiscretion", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "GlassPot" => 1.0,
    "ThornoFruit" => 1.0,
    "BailoPlant" => 1.0,
    "LichojPlant" => 1.0,
        ), array("BalmDiscretion" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume de survie
Build_19::addItem("BalmSurvival", true);
Build_19::addSkill("MakeBalmSurvival", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "GlassPot" => 1.0,
    "AloeJuice" => 1.0,
    "FlentoFlower" => 1.0,
    "LigioJuice" => 1.0,
    "LavoPlant" => 1.0,
        ), array("BalmSurvival" => 1.0), array("MortarAndPestle" => 1.0)
);

// Baume de survie
Build_19::addItem("BalmCaution", true);
Build_19::addSkill("MakeBalmCaution", "Perception", "Druid", null, array(
    "FatBasis" => 1.0,
    "GlassPot" => 1.0,
    "AbiFruit" => 1.0,
    "EikoPlant" => 1.0,
    "ThornoFruit" => 1.0,
    "BailoPlant" => 1.0,
        ), array("BalmCaution" => 1.0), array("MortarAndPestle" => 1.0)
);

// 1.3. Sirups (sirops)
Build_19::addItem("Flask", true); // Fiole
// Sirop de soins
Build_19::addItem("SirupHealth", true);
Build_19::addSkill("MakeSirupHealth", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AbiFruit" => 1.0,
    "FlentoFlower" => 1.0,
    "LavoPlant" => 1.0,
        ), array("SirupHealth" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop réhydratant
Build_19::addItem("SirupReHydrating", true);
Build_19::addSkill("MakeSirupReHydrating", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "BailoPlant" => 1.0,
    "ThornoFruit" => 1.0,
        ), array("SirupReHydrating" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop réhydratation
Build_19::addItem("SirupReHydration", true);
Build_19::addSkill("MakeSirupReHydration", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "ThornoFruit" => 1.0,
    "AloeJuice" => 1.0,
        ), array("SirupReHydration" => 1.0), array("MortarAndPestle" => 1.0)
);

// Additif Brûle vite
Build_19::addItem("SirupCombustion", true);
Build_19::addSkill("MakeSirupCombustion", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "PinFruit" => 1.0,
    "ThornoFruit" => 1.0,
        ), array("SirupCombustion" => 1.0), array("MortarAndPestle" => 1.0)
);

// Additif d'amélioration de rendement
Build_19::addItem("SirupEfficiency", true);
Build_19::addSkill("MakeSirupEfficiency", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "LigioJuice" => 1.0,
    "BailoPlant" => 1.0,
        ), array("SirupEfficiency" => 1.0), array("MortarAndPestle" => 1.0)
);


// Sirop de sens augmenté
Build_19::addItem("SirupSense", true);
Build_19::addSkill("MakeSirupSense", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "PinFruit" => 1.0,
    "LigioJuice" => 1.0,
    "BailoPlant" => 1.0,
        ), array("SirupSense" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop de préscience
Build_19::addItem("SirupPrescience", true);
Build_19::addSkill("MakeSirupPrescience", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AloeJuice" => 1.0,
    "AbiFruit" => 1.0,
    "PinFruit" => 1.0,
    "LavoPlant" => 1.0,
        ), array("SirupPrescience" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop de préscience
Build_19::addItem("SirupPrescience", true);
Build_19::addSkill("MakeSirupPrescience", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AloeJuice" => 1.0,
    "AbiFruit" => 1.0,
    "PinFruit" => 1.0,
    "LavoPlant" => 1.0,
        ), array("SirupPrescience" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop de préscience
Build_19::addItem("SirupAuthority", true);
Build_19::addSkill("MakeSirupAuthority", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AloeJuice" => 1.0,
    "PinFruit" => 1.0,
    "FlentoFlower" => 1.0,
    "EikoPlant" => 1.0,
        ), array("SirupAuthority" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop de préscience
Build_19::addItem("SirupGentle", true);
Build_19::addSkill("MakeSirupGentle", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AloeJuice" => 1.0,
    "PinFruit" => 1.0,
    "LavoPlant" => 1.0,
    "ThornoFruit" => 1.0,
    "LichojPlant" => 1.0,
        ), array("SirupGentle" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop de Résistance
Build_19::addItem("SirupResistance", true);
Build_19::addSkill("MakeSirupResistance", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AbiFruit" => 1.0,
    "PinFruit" => 1.0,
    "FlentoFlower" => 1.0,
        ), array("SirupResistance" => 1.0), array("MortarAndPestle" => 1.0)
);

// Sirop d'endurance
Build_19::addItem("SirupEndurance", true);
Build_19::addSkill("MakeSirupEndurance", "Perception", "Druid", null, array(
    "SugarBasis" => 1.0,
    "Flask" => 1.0,
    "AloeJuice" => 1.0,
    "PinFruit" => 1.0,
    "BailoPlant" => 1.0,
    "LichojPlant" => 1.0,
        ), array("SirupEndurance" => 1.0), array("MortarAndPestle" => 1.0)
);

// 1.4. Pills (gélules)
// Gélules d'énergie
Build_19::addItem("PillTime", true);
Build_19::addSkill("MakePillTime", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "ThornoFruit" => 1.0,
    "LichojPlant" => 1.0,
        ), array("PillTime" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules de regain d'énergie accélérée
Build_19::addItem("PillTimeImproved", true);
Build_19::addSkill("MakePillTimeImproved", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "ThornoFruit" => 1.0,
    "LigioJuice" => 1.0,
        ), array("PillTimeImproved" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules de regain d'énergie accélérée
Build_19::addItem("PillHungery", true);
Build_19::addSkill("MakePillHungery", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "FlentoFlower" => 1.0,
    "LichojPlant" => 1.0,
        ), array("PillHungery" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules de regain d'énergie accélérée
Build_19::addItem("PillHungeryImproved", true);
Build_19::addSkill("MakePillHungeryImproved", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AloeJuice" => 1.0,
    "FlentoFlower" => 1.0,
    "LichojPlant" => 1.0,
        ), array("PillHungeryImproved" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillMental", true);
Build_19::addSkill("MakePillMental", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AloeJuice" => 1.0,
    "LigioJuice" => 1.0,
    "BailoPlant" => 1.0,
        ), array("PillMental" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillCharm", true);
Build_19::addSkill("MakePillCharm", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AloeJuice" => 1.0,
    "LavoPlant" => 1.0,
    "EikoPlant" => 1.0,
    "BailoPlant" => 1.0,
        ), array("PillCharm" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillMeditative", true);
Build_19::addSkill("MakePillMeditative", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AbiFruit" => 1.0,
    "PinFruit" => 1.0,
    "EikoPlant" => 1.0,
    "BailoPlant" => 1.0,
        ), array("PillMeditative" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillIntuition", true);
Build_19::addSkill("MakePillIntuition", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AbiFruit" => 1.0,
    "LavoPlant" => 1.0,
    "ThornoFruit" => 1.0,
    "LichojPlant" => 1.0,
        ), array("PillIntuition" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillCapability", true);
Build_19::addSkill("MakePillCapability", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AbiFruit" => 1.0,
    "FlentoFlower" => 1.0,
    "LigioJuice" => 1.0,
    "BailoPlant" => 1.0,
    "LichojPlant" => 1.0,
        ), array("PillCapability" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillPsychic", true);
Build_19::addSkill("MakePillPsychic", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "AbiFruit" => 1.0,
    "LigioJuice" => 1.0,
    "LavoPlant" => 1.0,
    "EikoPlant" => 1.0,
    "ThornoFruit" => 1.0,
        ), array("PillPsychic" => 1.0), array("MortarAndPestle" => 1.0)
);

// Gélules d'émélioration mentale
Build_19::addItem("PillSeiza", true);
Build_19::addSkill("MakePillSeiza", "Perception", "Druid", null, array(
    "PillBasis" => 1.0,
    "PinFruit" => 1.0,
    "FlentoFlower" => 1.0,
    "LigioJuice" => 1.0,
    "LavoPlant" => 1.0,
    "EikoPlant" => 1.0,
        ), array("PillSeiza" => 1.0), array("MortarAndPestle" => 1.0)
);

// 2. Cuisine / Kitchen
// Ingrédients
Build_19::addItem("OliOil", true); // Huile d'Oli
Build_19::addItem("FlentoPowder", true); // Poudre de Flento
Build_19::addItem("AvoroCereal", true); // Céréales d'Avoro
Build_19::addItem("AvoroFlour", true); // Farine d'Avoro
Build_19::addItem("LichojFlour", true); // Farine de Lichoj
Build_19::addItem("AridoOil", true); // Huile d'Arido
Build_19::addItem("ThornoOil", true);
Build_19::addItem("Pectin", true); // Pectine

Build_19::addItem("RorroSpice", true); // Epice de Rorro

// contenants et objets divers
Build_19::addItem("Bowl", true); // Saladier
Build_19::addItem("Bottle", true); // Bouteille
Build_19::addItem("Bolus", true); // Bol
Build_19::addItem("Plate", true); // Assiette
// Outils
Build_19::addItem("Barrate", false); // Barrate
Build_19::AddEquipable("Barrate", "Hand", 1.0);

Build_19::addItem("Beater", false); // Fouet
Build_19::AddEquipable("Beater", "Hand", 1.0);

Build_19::addItem("Spoon", false); // Cuiller
Build_19::AddEquipable("Spoon", "Hand", 1.0);


// Barrater l'Oli
Build_19::addSkill("ChurningOli", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "BasketeryPot" => 1.0,
        ), array("OliButter" => 3.0), array("Barrate" => 1.0)
);

// Barrater l'Arido
Build_19::addSkill("ChurningArido", "Perception", "Kitchen", null, array(
    "AridoOil" => 1.0,
    "BasketeryPot" => 1.0,
        ), array("AridoButter" => 3.0), array("Barrate" => 1.0)
);

// Barrater le thorno
Build_19::addSkill("ChurningThorno", "Perception", "Kitchen", null, array(
    "ThornoOil" => 1.0,
    "BasketeryPot" => 1.0,
        ), array("ThornoButter" => 3.0), array("Barrate" => 1.0)
);

// Cuisiner Salade de Flento
Build_19::addItem("FlentoSalad", true);
Build_19::addSkill("CookFlentoSalad", "Perception", "Kitchen", null, array(
    "FlentoFlower" => 1.0,
    "RorroSpice" => 1.0,
    "AridoOil" => 1.0,
    "Bowl" => 2.0,
        ), array("FlentoSalad" => 2.0), array("Knife" => 1.0)
);

// Cuisiner Salade de Fiko
Build_19::addItem("FikoSalad", true);
Build_19::addSkill("CookFikoSalad", "Perception", "Kitchen", null, array(
    "FikoPlant" => 1.0,
    "RorroSpice" => 1.0,
    "OliOil" => 1.0,
    "Bowl" => 2.0,
        ), array("FikoSalad" => 2.0), array("Knife" => 1.0)
);

// Cuisiner Salade de Lichoj
Build_19::addItem("LichojSalad", true);
Build_19::addSkill("CookLichojSalad", "Perception", "Kitchen", null, array(
    "LichojPlant" => 1.0,
    "RorroSpice" => 1.0,
    "OliOil" => 1.0,
    "Bowl" => 2.0,
        ), array("LichojSalad" => 2.0), array("Knife" => 1.0)
);

// Cuisiner Salade de Beano
Build_19::addItem("BeanoSalad", true);
Build_19::addSkill("CookBeanoSalad", "Perception", "Kitchen", null, array(
    "BeanoHull" => 1.0,
    "RorroSpice" => 1.0,
    "AridoOil" => 1.0,
    "Bowl" => 2.0,
        ), array("BeanoSalad" => 2.0), array("Knife" => 1.0)
);

// Griller de l'Adano
Build_19::addItem("AdanoRoasted", true);
Build_19::addSkill("CookAdanoRoasted", "Perception", "Kitchen", 1.0, array(
    "BaoFruit" => 1.0,
    "OliOil" => 1.0,
    "Coal" => 1.0,
    "BasketeryPot" => 2.0,
        ), array("AdanoRoasted" => 2.0), array()
);

// Griller du Piño
Build_19::addItem("PinoRoasted", true);
Build_19::addSkill("CookPinoRoasted", "Perception", "Kitchen", 1.0, array(
    "PinFruit" => 1.0,
    "OliOil" => 1.0,
    "Coal" => 1.0,
    "BasketeryPot" => 2.0,
        ), array("PinoRoasted" => 2.0), array()
);

// Griller de l'arido
Build_19::addItem("AridoRoasted", true);
Build_19::addSkill("CookAridoRoasted", "Perception", "Kitchen", 1.0, array(
    "AridoSeed" => 1.0,
    "AridoOil" => 1.0,
    "Coal" => 1.0,
    "BasketeryPot" => 2.0,
        ), array("AridoRoasted" => 2.0), array()
);

// Mixer boisson frappée au thorno
Build_19::addItem("ThornoSmoothie", true);
Build_19::addSkill("CookThornoSmoothie", "Perception", "Kitchen", null, array(
    "BaoFruit" => 1.0,
    "ThornoFruit" => 1.0,
    "Bottle" => 5.0,
        ), array("ThornoSmoothie" => 5.0), array("Beater" => 1.0)
);

// Mixer boisson frappée au airelles
Build_19::addItem("BeroSmoothie", true);
Build_19::addSkill("CookBeroSmoothie", "Perception", "Kitchen", null, array(
    "BaoFruit" => 1.0,
    "BeroFruit" => 1.0,
    "Bottle" => 5.0,
        ), array("BeroSmoothie" => 5.0), array("Beater" => 1.0)
);

// Mixer boisson frappée au fangsorxo
Build_19::addItem("FangsorxoSmoothie", true);
Build_19::addSkill("CookFangsorxoSmoothie", "Perception", "Kitchen", null, array(
    "BaoFruit" => 1.0,
    "FangsorxoFruit" => 1.0,
    "Bottle" => 5.0,
        ), array("FangsorxoSmoothie" => 5.0), array("Beater" => 1.0)
);

// Muesli au thorno
Build_19::addItem("ThornoMuesli", true);
Build_19::addSkill("CookThornoMuesli", "Perception", "Kitchen", null, array(
    "BaoFruit" => 1.0,
    "ThornoFruit" => 1.0,
    "Bolus" => 1.0,
        ), array("ThornoMuesli" => 1.0), array("Knife" => 1.0)
);

// Muesli au airelles
Build_19::addItem("BeroMuesli", true);
Build_19::addSkill("CookBeroMuesli", "Perception", "Kitchen", null, array(
    "BaoFruit" => 1.0,
    "BeroFruit" => 1.0,
    "Bolus" => 1.0,
        ), array("BeroMuesli" => 1.0), array("Knife" => 1.0)
);

// Muesli au fangsorxo
Build_19::addItem("FangsorxoMuesli", true);
Build_19::addSkill("CookFangsorxoMuesli", "Perception", "Kitchen", null, array(
    "BaoFruit" => 1.0,
    "FangsorxoFruit" => 1.0,
    "Bolus" => 1.0,
        ), array("FangsorxoMuesli" => 1.0), array("Knife" => 1.0)
);

// Piño confit
Build_19::addItem("PinCandied", true);
Build_19::addSkill("CookPinCandied", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "PinFruit" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("PinCandied" => 1.0), array("Spoon" => 1.0)
);

// Flento confit
Build_19::addItem("FlentoCandied", true);
Build_19::addSkill("CookFlentoCandied", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "FlentoFlower" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("FlentoCandied" => 1.0), array("Spoon" => 1.0)
);

// Lavo confit
Build_19::addItem("LavoCandied", true);
Build_19::addSkill("CookLavoCandied", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "LavoPlant" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("LavoCandied" => 1.0), array("Spoon" => 1.0)
);

// Eiko confit
Build_19::addItem("EikoCandied", true);
Build_19::addSkill("CookEikoCandied", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "EikoPlant" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("EikoCandied" => 1.0), array("Spoon" => 1.0)
);

// confiture de thorno
Build_19::addItem("ThornoJam", true);
Build_19::addSkill("CookThornoJam", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "ThornoFruit" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("ThornoJam" => 1.0), array("Spoon" => 1.0)
);

// confiture d'airelles
Build_19::addItem("BeroJam", true);
Build_19::addSkill("CookBeroJam", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "BeroFruit" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("BeroJam" => 1.0), array("Spoon" => 1.0)
);

// confiture de squo
Build_19::addItem("SquoJam", true);
Build_19::addSkill("CookSquoJam", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "SquoPlant" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("SquoJam" => 1.0), array("Spoon" => 1.0)
);

// confiture de fangsorxo
Build_19::addItem("FangsorxoJam", true);
Build_19::addSkill("CookFangsorxoJam", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "FangsorxoFruit" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("FangsorxoJam" => 1.0), array("Spoon" => 1.0)
);

// confiture de Kakto
Build_19::addItem("KaktoJam", true);
Build_19::addSkill("CookKaktoJam", "Perception", "Kitchen", null, array(
    "Pectin" => 1.0,
    "Sugar" => 1.0,
    "KaktoPlant" => 1.0,
    "Coal" => 1.0,
    "GlassPot" => 1.0,
        ), array("KaktoJam" => 1.0), array("Spoon" => 1.0)
);

// Gâteau de piño
Build_19::addItem("PinoCake", true);
Build_19::addSkill("CookPinoCake", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "Sugar" => 1.0,
    "LichojFlour" => 1.0,
    "PinFruit" => 1.0,
    "Coal" => 1.0,
        ), array("PinoCake" => 10.0), array("Spoon" => 1.0)
);

// Gâteau de thorno
Build_19::addItem("ThornoCake", true);
Build_19::addSkill("CookThornoCake", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "Sugar" => 1.0,
    "LichojFlour" => 1.0,
    "ThornoFruit" => 1.0,
    "Coal" => 1.0,
        ), array("ThornoCake" => 10.0), array("Spoon" => 1.0)
);

// Gâteau aux airelles
Build_19::addItem("BeroCake", true);
Build_19::addSkill("CookBeroCake", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "Sugar" => 1.0,
    "LichojFlour" => 1.0,
    "BeroFruit" => 1.0,
    "Coal" => 1.0,
        ), array("BeroCake" => 10.0), array("Spoon" => 1.0)
);

// Gâteau de squo
Build_19::addItem("SquoCake", true);
Build_19::addSkill("CookSquoCake", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "Sugar" => 1.0,
    "AvoroFlour" => 1.0,
    "SquoPlant" => 1.0,
    "Coal" => 1.0,
        ), array("SquoCake" => 10.0), array("Spoon" => 1.0)
);

// Gâteau de fangsorxo
Build_19::addItem("FangsorxoCake", true);
Build_19::addSkill("CookFangsorxoCake", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "Sugar" => 1.0,
    "AvoroFlour" => 1.0,
    "FangsorxoFruit" => 1.0,
    "Coal" => 1.0,
        ), array("FangsorxoCake" => 10.0), array("Spoon" => 1.0)
);

// Gâteau de Kakto
Build_19::addItem("KaktoCake", true);
Build_19::addSkill("CookKaktoCake", "Perception", "Kitchen", null, array(
    "OliOil" => 1.0,
    "Sugar" => 1.0,
    "AvoroFlour" => 1.0,
    "KaktoPlant" => 1.0,
    "Coal" => 1.0,
        ), array("KaktoCake" => 10.0), array("Spoon" => 1.0)
);

// Lait de piño
Build_19::addItem("PinoMilk", true);
Build_19::addSkill("CookPinoMilk", "Perception", "Kitchen", null, array(
    "Water" => 5.0,
    "PinFruit" => 1.0,
    "Coal" => 1.0,
    "Bottle" => 5.0,
        ), array("PinoMilk" => 5.0), array("MortarAndPestle" => 1.0)
);

// Lait d'Arido
Build_19::addItem("AridoMilk", true);
Build_19::addSkill("CookAridoMilk", "Perception", "Kitchen", null, array(
    "Water" => 5.0,
    "AridoSeed" => 1.0,
    "Coal" => 1.0,
    "Bottle" => 5.0,
        ), array("AridoMilk" => 5.0), array("MortarAndPestle" => 1.0)
);

// Lait de Ligio
Build_19::addItem("LigioMilk", true);
Build_19::addSkill("CookLigioMilk", "Perception", "Kitchen", null, array(
    "Water" => 5.0,
    "LigioPlant" => 1.0,
    "Coal" => 1.0,
    "Bottle" => 5.0,
        ), array("LigioMilk" => 5.0), array("MortarAndPestle" => 1.0)
);

// Fromage végétal
Build_19::addItem("CheeseBasis", true);
Build_19::addSkill("CookCheeseBasisPino", "Perception", "Kitchen", null, array(
    "PinoMilk" => 1.0,
    "Bolus" => 1.0,
        ), array("CheeseBasis" => 1.0), array("Spoon" => 1.0)
);
Build_19::addSkill("CookCheeseBasisArido", "Perception", "Kitchen", null, array(
    "AridoMilk" => 1.0,
    "Bolus" => 1.0,
        ), array("CheeseBasis" => 1.0), array("Spoon" => 1.0)
);
Build_19::addSkill("CookCheeseBasisLigio", "Perception", "Kitchen", null, array(
    "LigioMilk" => 1.0,
    "Bolus" => 1.0,
        ), array("CheeseBasis" => 1.0), array("Spoon" => 1.0)
);

// Fromage au Flento
Build_19::addItem("FlentoCheese", true);
Build_19::addSkill("CookFlentoCheese", "Perception", "Kitchen", null, array(
    "CheeseBasis" => 1.0,
    "FlentoPowder" => 1.0,
    "Bolus" => 10.0,
        ), array("FlentoCheese" => 10.0), array("Spoon" => 1.0)
);

// Fromage au Rorro
Build_19::addItem("RorroCheese", true);
Build_19::addSkill("CookRorroCheese", "Perception", "Kitchen", null, array(
    "CheeseBasis" => 1.0,
    "RorroSpice" => 1.0,
    "Bolus" => 10.0,
        ), array("RorroCheese" => 10.0), array("Spoon" => 1.0)
);


// Bonbon au Flento
Build_19::addItem("FlentoCandy", true);
Build_19::addSkill("CookFlentoCandy", "Perception", "Kitchen", null, array(
    "Sugar" => 1.0,
    "FlentoPowder" => 1.0,
    "Coal" => 1.0,
        ), array("FlentoCandy" => 20.0), array("Spoon" => 1.0)
);

// Bonbon au Lavo
Build_19::addItem("LavoCandy", true);
Build_19::addSkill("CookLavoCandy", "Perception", "Kitchen", null, array(
    "Sugar" => 1.0,
    "LavoPlant" => 1.0,
    "Coal" => 1.0,
        ), array("LavoCandy" => 20.0), array("Spoon" => 1.0)
);

// Bonbon aux airelles
Build_19::addItem("BeroCandy", true);
Build_19::addSkill("CookBeroCandy", "Perception", "Kitchen", null, array(
    "Sugar" => 1.0,
    "BeroJuice" => 1.0,
    "Coal" => 1.0,
        ), array("BeroCandy" => 20.0), array("Spoon" => 1.0)
);

// Céréales au Thorno
Build_19::addItem("ThornoCereal", true);
Build_19::addSkill("CookThornoCereal", "Perception", "Kitchen", null, array(
    "AvoroCereal" => 1.0,
    "ThornoFruit" => 1.0,
    "LigioMilk" => 1.0,
    "Bolus" => 1.0,
        ), array("ThornoCereal" => 1.0), array("Spoon" => 1.0)
);

// Céréales au Airelles
Build_19::addItem("BeroCereal", true);
Build_19::addSkill("CookBeroCereal", "Perception", "Kitchen", null, array(
    "AvoroCereal" => 1.0,
    "BeroFruit" => 1.0,
    "LigioMilk" => 1.0,
    "Bolus" => 1.0,
        ), array("BeroCereal" => 1.0), array("Spoon" => 1.0)
);

// Céréales au Fangsorxo
Build_19::addItem("FangsorxoCereal", true);
Build_19::addSkill("CookFangsorxoCereal", "Perception", "Kitchen", null, array(
    "AvoroCereal" => 1.0,
    "FangsorxoFruit" => 1.0,
    "LigioMilk" => 1.0,
    "Bolus" => 1.0,
        ), array("FangsorxoCereal" => 1.0), array("Spoon" => 1.0)
);

// Pain d'avoro
Build_19::addItem("AvoroBread", true);
Build_19::addSkill("CookAvoroBread", "Perception", "Kitchen", null, array(
    "AvoroFlour" => 1.0,
    "Water" => 1.0,
    "Coal" => 1.0,
        ), array("AvoroBread" => 10.0), array("Spoon" => 1.0)
);

// Pain de Lichoj
Build_19::addItem("LichojBread", true);
Build_19::addSkill("CookLichojBread", "Perception", "Kitchen", null, array(
    "LichojFlour" => 1.0,
    "Water" => 1.0,
    "Coal" => 1.0,
        ), array("LichojBread" => 10.0), array("Spoon" => 1.0)
);

// Pates
Build_19::addItem("AvoroPasta", true);
Build_19::addSkill("CookAvoroPasta", "Perception", "Kitchen", null, array(
    "AvoroFlour" => 1.0,
    "Water" => 1.0,
    "AvoroCereal" => 1.0,
    "Coal" => 1.0,
    "Bolus" => 10.0,
        ), array("AvoroPasta" => 10.0), array("Spoon" => 1.0)
);

// Crepes d'Avoro
Build_19::addItem("AvoroPancakes", true);
Build_19::addSkill("CookAvoroPancakes", "Perception", "Kitchen", null, array(
    "AvoroFlour" => 1.0,
    "AridoMilk" => 1.0,
    "Coal" => 1.0,
    "Plate" => 10.0,
        ), array("AvoroPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes de Lichoj
Build_19::addItem("LichojPancakes", true);
Build_19::addSkill("CookLichojPancakes", "Perception", "Kitchen", null, array(
    "LichojFlour" => 1.0,
    "LigioMilk" => 1.0,
    "Coal" => 1.0,
    "Plate" => 10.0,
        ), array("LichojPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Pino
Build_19::addItem("PinoAvoroPancakes", true);
Build_19::addSkill("CookPinoAvoroPancakes", "Perception", "Kitchen", null, array(
    "AvoroPancakes" => 10.0,
    "PinCandied" => 1.0,
        ), array("PinoAvoroPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Flento
Build_19::addItem("FlentoAvoroPancakes", true);
Build_19::addSkill("CookFlentoAvoroPancakes", "Perception", "Kitchen", null, array(
    "AvoroPancakes" => 10.0,
    "FlentoCandied" => 1.0,
        ), array("FlentoAvoroPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Lavo
Build_19::addItem("LavoAvoroPancakes", true);
Build_19::addSkill("CookLavoAvoroPancakes", "Perception", "Kitchen", null, array(
    "AvoroPancakes" => 10.0,
    "LavoCandied" => 1.0,
        ), array("LavoAvoroPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes à L'Eiko
Build_19::addItem("EikoAvoroPancakes", true);
Build_19::addSkill("CookEikoAvoroPancakes", "Perception", "Kitchen", null, array(
    "AvoroPancakes" => 10.0,
    "EikoCandied" => 1.0,
        ), array("EikoAvoroPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Thorno
Build_19::addItem("ThornoLichojPancakes", true);
Build_19::addSkill("CookThornoLichojPancakes", "Perception", "Kitchen", null, array(
    "LichojPancakes" => 10.0,
    "ThornoJam" => 1.0,
        ), array("ThornoLichojPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes aux airelles
Build_19::addItem("BeroLichojPancakes", true);
Build_19::addSkill("CookBeroLichojPancakes", "Perception", "Kitchen", null, array(
    "LichojPancakes" => 10.0,
    "BeroJam" => 1.0,
        ), array("BeroLichojPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Squo
Build_19::addItem("SquoLichojPancakes", true);
Build_19::addSkill("CookSquoLichojPancakes", "Perception", "Kitchen", null, array(
    "LichojPancakes" => 10.0,
    "SquoJam" => 1.0,
        ), array("SquoLichojPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Fangsorxo
Build_19::addItem("FangsorxoLichojPancakes", true);
Build_19::addSkill("CookFangsorxoLichojPancakes", "Perception", "Kitchen", null, array(
    "LichojPancakes" => 10.0,
    "FangsorxoJam" => 1.0,
        ), array("FangsorxoLichojPancakes" => 10.0), array("Spoon" => 1.0)
);

// Crepes au Kakto
Build_19::addItem("KaktoLichojPancakes", true);
Build_19::addSkill("CookKaktoLichojPancakes", "Perception", "Kitchen", null, array(
    "LichojPancakes" => 10.0,
    "KaktoJam" => 1.0,
        ), array("KaktoLichojPancakes" => 10.0), array("Spoon" => 1.0)
);

// Soupe de Rorro
Build_19::addItem("RorroSoup", true);
Build_19::addSkill("CookRorroSoup", "Perception", "Kitchen", null, array(
    "Water" => 1.0,
    "RorroPlant" => 1.0,
    "Coal" => 1.0,
    "Bolus" => 1.0,
        ), array("RorroSoup" => 1.0), array("Spoon" => 1.0)
);

// Soupe de Fiko
Build_19::addItem("FikoSoup", true);
Build_19::addSkill("CookFikoSoup", "Perception", "Kitchen", null, array(
    "Water" => 1.0,
    "FikoPlant" => 1.0,
    "Coal" => 1.0,
    "Bolus" => 1.0,
        ), array("FikoSoup" => 1.0), array("Spoon" => 1.0)
);

// Soupe de Lichoj
Build_19::addItem("LichojSoup", true);
Build_19::addSkill("CookLichojSoup", "Perception", "Kitchen", null, array(
    "Water" => 1.0,
    "LichojPlant" => 1.0,
    "Coal" => 1.0,
    "Bolus" => 1.0,
        ), array("LichojSoup" => 1.0), array("Spoon" => 1.0)
);

// Soupe de Squo
Build_19::addItem("SquoSoup", true);
Build_19::addSkill("CookSquoSoup", "Perception", "Kitchen", null, array(
    "Water" => 1.0,
    "SquoPlant" => 1.0,
    "Coal" => 1.0,
    "Bolus" => 1.0,
        ), array("SquoSoup" => 1.0), array("Spoon" => 1.0)
);

// Gratin de Squo
Build_19::addItem("SquoGratin", true);
Build_19::addSkill("CookSquoGratin", "Perception", "Kitchen", null, array(
    "SquoPlant" => 1.0,
    "Coal" => 1.0,
    "Plate" => 1.0,
        ), array("SquoGratin" => 1.0), array("Spoon" => 1.0)
);

// Beano à la vapeur
Build_19::addItem("BeanoSteamed", true);
Build_19::addSkill("CookBeanoSteamed", "Perception", "Kitchen", null, array(
    "BeanoHull" => 1.0,
    "Coal" => 1.0,
    "Plate" => 1.0,
        ), array("BeanoSteamed" => 1.0), array("Spoon" => 1.0)
);


// 3. Scierie		
Build_19::addItem("Plank", true); // Planche
//Scier du Bet
Build_19::addSkill("SawBet", "Strength", "Sawmill", 1.0, array(
    "BetTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 10.0), array()
);

//Scier du Salik
Build_19::addSkill("SawSalik", "Strength", "Sawmill", 1.0, array(
    "SalikTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 10.0), array()
);

//Scier du Kver
Build_19::addSkill("SawKver", "Strength", "Sawmill", 1.0, array(
    "KverTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 15.0), array()
);

//Scier du Spruc
Build_19::addSkill("SawSpruc", "Strength", "Sawmill", 1.0, array(
    "SprucTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 10.0), array()
);

//Scier du Larik
Build_19::addSkill("SawLarik", "Strength", "Sawmill", 1.0, array(
    "LarikTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 10.0), array()
);

//Scier du Pin
Build_19::addSkill("SawPin", "Strength", "Sawmill", 1.0, array(
    "PinTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 4.0), array()
);

//Scier de l'Abi
Build_19::addSkill("SawAbi", "Strength", "Sawmill", 1.0, array(
    "AbiTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 1.0), array()
);

//Scier du Bao
Build_19::addSkill("SawBao", "Strength", "Sawmill", 1.0, array(
    "BaoTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 10.0), array()
);

//Scier de l'Oli
Build_19::addSkill("SawOli", "Strength", "Sawmill", 1.0, array(
    "OliTimber" => 1.0,
    "Coal" => 1.0,
        ), array("Plank" => 1.0), array()
);

// Pot en bois
Build_19::addItem("WoodLathe", false); // Tour à bois	
Build_19::AddEquipable("WoodLathe", "Hand", 1.0);

Build_19::addItem("RopeAndGouge", false); // Corde et Gouge
Build_19::AddEquipable("RopeAndGouge", "Hand", 1.0);

Build_19::addSkill("MakeBetWoodPot", "Strength", "Sawmill", null, array(
    "BetTimber" => 1.0,
        ), array("WoodPot" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeKverWoodPot", "Strength", "Sawmill", null, array(
    "KverTimber" => 1.0,
        ), array("WoodPot" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

// Cuiller
Build_19::addSkill("MakeBetSpoon", "Strength", "Sawmill", null, array(
    "BetTimber" => 1.0,
        ), array("Spoon" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeSalikSpoon", "Strength", "Sawmill", null, array(
    "SalikTimber" => 1.0,
        ), array("Spoon" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeSprucSpoon", "Strength", "Sawmill", null, array(
    "SprucTimber" => 1.0,
        ), array("Spoon" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

// Saladier

Build_19::addSkill("MakeSalikBowl", "Strength", "Sawmill", null, array(
    "SalikTimber" => 1.0,
        ), array("Bowl" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeAbiBowl", "Strength", "Sawmill", null, array(
    "AbiTimber" => 1.0,
        ), array("Bowl" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

// Bol

Build_19::addSkill("MakeSalikBolus", "Strength", "Sawmill", null, array(
    "SalikTimber" => 1.0,
        ), array("Bolus" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeAbiBolus", "Strength", "Sawmill", null, array(
    "AbiTimber" => 1.0,
        ), array("Bolus" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

//Assiette

Build_19::addSkill("MakeBetPlate", "Strength", "Sawmill", null, array(
    "BetTimber" => 1.0,
        ), array("Plate" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeSprucPlate", "Strength", "Sawmill", null, array(
    "SprucTimber" => 1.0,
        ), array("Plate" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

Build_19::addSkill("MakeLarikPlate", "Strength", "Sawmill", null, array(
    "LarikTimber" => 1.0,
        ), array("Plate" => 10.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

//Peigne

Build_19::addSkill("MakeComb", "Strength", "Sawmill", null, array(
    "Plank" => 1.0,
        ), array("Comb" => 1.0), array("Saw" => 1.0)
);

// Seau

Build_19::addSkill("MakeKverSealLiquid", "Strength", "Sawmill", null, array(
    "KverTimber" => 1.0,
        ), array("SealLiquid" => 1.0), array("Saw" => 1.0)
);

Build_19::addSkill("MakePinSealLiquid", "Strength", "Sawmill", null, array(
    "PinTimber" => 1.0,
        ), array("SealLiquid" => 1.0), array("Saw" => 1.0)
);

//Mortier et pilon

Build_19::addSkill("MakeBetMortarAndPestle", "Strength", "Sawmill", null, array(
    "BetTimber" => 1.0,
        ), array("MortarAndPestle" => 1.0), array(
    "WoodLathe" => 1.0,
    "RopeAndGouge" => 1.0,
        )
);

// Barrate

Build_19::addSkill("MakeKverBarrate", "Strength", "Sawmill", null, array(
    "KverTimber" => 1.0,
        ), array("Barrate" => 1.0), array("Saw" => 1.0)
);

// Outils de cordier
Build_19::addItem("Ropewalk", false);
Build_19::AddEquipable("Ropewalk", "Hand", 1.0);

Build_19::addSkill("MakeRopewalk", "Strength", "Sawmill", null, array(
    "Plank" => 5.0,
        ), array("Ropewalk" => 1.0), array("Saw" => 1.0)
);

// 4. Charbonière		

Build_19::addSkill("PyrolyzingBet", "Perception", "Coal", null, array(
    "BetTimber" => 1.0,
        ), array("Coal" => 10.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingKver", "Perception", "Coal", null, array(
    "KverTimber" => 1.0,
        ), array("Coal" => 15.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingBao", "Perception", "Coal", null, array(
    "BaoTimber" => 1.0,
        ), array("Coal" => 10.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingPin", "Perception", "Coal", null, array(
    "PinTimber" => 1.0,
        ), array("Coal" => 10.0), array("Glove" => 1.0)
);
Build_19::addSkill("PyrolyzingAbi", "Perception", "Coal", null, array(
    "AbiTimber" => 1.0,
        ), array("Coal" => 1.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingLarik", "Perception", "Coal", null, array(
    "LarikTimber" => 1.0,
        ), array("Coal" => 5.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingSpruc", "Perception", "Coal", null, array(
    "SprucTimber" => 1.0,
        ), array("Coal" => 2.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingOli", "Perception", "Coal", null, array(
    "OliTimber" => 1.0,
        ), array("Coal" => 1.0), array("Glove" => 1.0)
);

Build_19::addSkill("PyrolyzingSalik", "Perception", "Coal", null, array(
    "SalikTimber" => 1.0,
        ), array("Coal" => 2.0), array("Glove" => 1.0)
);

//5. Cokerie

Build_19::addItem("Coke", true);
Build_19::addSkill("PyrolyzingCoal", "Perception", "Cokery", 1.0, array(
    "Coal" => 10.0,
        ), array("Coke" => 1.0), array()
);

//6. Bas fourneau
Build_19::addItem("CastIron", true); //Fonte
Build_19::addItem("Steel", true); //Acier

Build_19::addSkill("ProduceCastIron", "Strength", "LowFurnace", 1.0, array(
    "IronOre" => 1.0,
    "Coal" => 1.0,
        ), array("CastIron" => 1.0), array()
);

Build_19::addSkill("ProduceSteel", "Strength", "LowFurnace", 1.0, array(
    "CastIron" => 1.0,
    "Coal" => 1.0,
        ), array("Steel" => 1.0), array()
);

//7. Haut fourneau

Build_19::addSkill("MakeCastIron", "Strength", "BlastFurnace", 1.0, array(
    "IronOre" => 10.0,
    "Coke" => 1.0,
        ), array("CastIron" => 10.0), array()
);

Build_19::addSkill("MakeSteel", "Strength", "BlastFurnace", 1.0, array(
    "CastIron" => 10.0,
    "Coke" => 1.0,
        ), array("Steel" => 10.0), array()
);

//8. Forge
Build_19::addItem("BlacksmithTools", false);
Build_19::AddEquipable("BlacksmithTools", "Hand", 1.0);

Build_19::addSkill("MakeTube", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Coal" => 1.0,
        ), array("Tube" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeSaw", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Saw" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeAxe", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Axe" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeSerpe", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Serpe" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeSecateur", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Secateur" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeScythe", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Scythe" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeKnife", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Knife" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeGathererKnife", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("GathererKnife" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeSawOneHand", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("SawOneHand" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeShovel", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Shovel" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakePickaxe", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Pickaxe" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeBeater", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Coal" => 1.0,
        ), array("Beater" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addItem("Hammer", false);
Build_19::AddEquipable("Hammer", "Hand", 1.0);

Build_19::addSkill("MakeHammer", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("Hammer" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeGourd", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Coal" => 1.0,
        ), array("Gourd" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeWoodLathe", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Coal" => 1.0,
        ), array("WoodLathe" => 1.0), array("BlacksmithTools" => 1.0)
);

Build_19::addSkill("MakeBlacksmithTools", "Strength", "Forge", null, array(
    "Steel" => 1.0,
    "Plank" => 1.0,
    "Coal" => 1.0,
        ), array("BlacksmithTools" => 1.0), array("BlacksmithTools" => 1.0)
);

//9. Sechoir		

Build_19::addItem("LigioDried", true);
Build_19::addSkill("MakeLigioDried", "Perception", "Drying", 1.0, array(
    "LigioPlant" => 1.0,
    "Coal" => 1.0,
        ), array("LigioDried" => 1.0), array()
);

Build_19::addItem("JarkiloDried", true);
Build_19::addSkill("MakeJarkiloDried", "Perception", "Drying", 1.0, array(
    "JarkiloStem" => 1.0,
    "Coal" => 1.0,
        ), array("JarkiloDried" => 1.0), array()
);

Build_19::addItem("BailoDried", true);
Build_19::addSkill("MakeBailoDried", "Perception", "Drying", 1.0, array(
    "BailoPlant" => 1.0,
    "Coal" => 1.0,
        ), array("BailoDried" => 1.0), array()
);

Build_19::addItem("FikoDried", true);
Build_19::addSkill("MakeFikoDried", "Perception", "Drying", 1.0, array(
    "FikoPlant" => 1.0,
    "Coal" => 1.0,
        ), array("FikoDried" => 1.0), array()
);

Build_19::addItem("LichojDried", true);
Build_19::addSkill("MakeLichojDried", "Perception", "Drying", 1.0, array(
    "LichojPlant" => 1.0,
    "Coal" => 1.0,
        ), array("LichojDried" => 1.0), array()
);

Build_19::addSkill("MakeBeroPectin", "Perception", "Drying", 1.0, array(
    "BeroFruit" => 1.0,
    "Coal" => 1.0,
        ), array("Pectin" => 1.0), array()
);

Build_19::addSkill("MakeFlentoPowder", "Perception", "Drying", 1.0, array(
    "FlentoFlower" => 1.0,
    "Coal" => 1.0,
        ), array("FlentoPowder" => 10.0), array()
);

Build_19::addSkill("MakeRorroSpice", "Perception", "Drying", 1.0, array(
    "RorroPlant" => 1.0,
    "Coal" => 1.0,
        ), array("RorroSpice" => 10.0), array()
);

// 10. Cabane de Vannerie

Build_19::addItem("Rope", true);

Build_19::addSkill("MakeLigioRope", "Strength", "Basketry", null, array(
    "LigioDried" => 1.0,
        ), array("Rope" => 1.0), array("Ropewalk" => 1.0)
);

Build_19::addSkill("MakeBailoRope", "Strength", "Basketry", null, array(
    "BailoDried" => 1.0,
        ), array("Rope" => 1.0), array("Ropewalk" => 1.0)
);

Build_19::addSkill("MakeJarkiloBasketeryPot", "Strength", "Basketry", 1.0, array(
    "JarkiloDried" => 1.0,
        ), array("BasketeryPot" => 10.0), array()
);

Build_19::addSkill("MakeBailoBasketeryPot", "Strength", "Basketry", 1.0, array(
    "BailoDried" => 1.0,
        ), array("BasketeryPot" => 10.0), array()
);

Build_19::addSkill("MakeRopeAndGouge", "Strength", "Basketry", 1.0, array(
    "Rope" => 1.0,
    "Plank" => 1.0,
        ), array("RopeAndGouge" => 1.0), array()
);

//11. Moulin

Build_19::addSkill("MakeOliOil", "Strength", "Mill", 1.0, array(
    "OliFruit" => 1.0,
    "Coal" => 1.0,
        ), array("OliOil" => 10.0), array()
);

Build_19::addSkill("MakeLigioJuice", "Strength", "Mill", 1.0, array(
    "LigioPlant" => 1.0,
    "Coal" => 1.0,
        ), array("LigioJuice" => 10.0), array()
);

Build_19::addSkill("MakeAloeJuice", "Strength", "Mill", 1.0, array(
    "AloePlant" => 1.0,
    "Coal" => 1.0,
        ), array("AloeJuice" => 10.0), array()
);

Build_19::addSkill("MakeKaktoJuice", "Strength", "Mill", 1.0, array(
    "KaktoPlant" => 1.0,
    "Coal" => 1.0,
        ), array("KaktoJuice" => 10.0), array()
);

Build_19::addSkill("MakeAridoOil", "Strength", "Mill", 1.0, array(
    "AridoSeed" => 1.0,
    "Coal" => 1.0,
        ), array("AridoOil" => 10.0), array()
);

Build_19::addItem("AvoroStraw", true); //paille

Build_19::addSkill("MakeAvoroCereal", "Strength", "Mill", 1.0, array(
    "AvoroStem" => 1.0,
    "Coal" => 1.0,
        ), array(
            "AvoroCereal" => 1.0,
            "AvoroStraw" => 1.0
        ), array()
);

Build_19::addSkill("MakeAvoroFlour", "Strength", "Mill", 1.0, array(
    "AvoroCereal" => 1.0,
    "Coal" => 1.0,
        ), array("AvoroFlour" => 1.0), array()
);

Build_19::addSkill("MakeLichojFlour", "Strength", "Mill", 1.0, array(
    "LichojDried" => 1.0,
    "Coal" => 1.0,
        ), array("LichojFlour" => 1.0), array()
);

Build_19::addSkill("MakeAvoroJuice", "Strength", "Mill", 1.0, array(
    "AvoroStem" => 1.0,
    "Coal" => 1.0,
        ), array("AvoroJuice" => 10.0), array()
);

Build_19::addSkill("MakeBaoJuice", "Strength", "Mill", 1.0, array(
    "BaoFruit" => 1.0,
    "Coal" => 1.0,
        ), array("BaoJuice" => 10.0), array()
);

Build_19::addSkill("MakeBeroJuice", "Strength", "Mill", 1.0, array(
    "BeroFruit" => 1.0,
    "Coal" => 1.0,
        ), array("BeroJuice" => 10.0), array()
);

Build_19::addSkill("MakeThornoOil", "Strength", "Mill", 1.0, array(
    "ThornoFruit" => 1.0,
    "Coal" => 1.0,
        ), array("ThornoOil" => 10.0), array()
);

Build_19::addSkill("MakeThornoJuice", "Strength", "Mill", 1.0, array(
    "ThornoFruit" => 1.0,
    "Coal" => 1.0,
        ), array("ThornoJuice" => 10.0), array()
);

Build_19::addSkill("MakeAloeGel", "Strength", "Mill", 1.0, array(
    "AloeJuice" => 1.0,
    "Coal" => 1.0,
        ), array("AloeGel" => 1.0), array()
);

//12. Verrerie
Build_19::addItem("Glass", true);

Build_19::addSkill("MakeGlass", "Strength", "Glassware", 1.0, array(
    "Sand" => 1.0,
    "Coal" => 1.0,
        ), array("Glass" => 1.0), array()
);

Build_19::addSkill("MakeBottle", "Strength", "Glassware", 1.0, array(
    "Glass" => 1.0,
    "Coal" => 1.0,
        ), array("Bottle" => 10.0), array()
);

Build_19::addSkill("MakeFlask", "Strength", "Glassware", 1.0, array(
    "Glass" => 1.0,
    "Coal" => 1.0,
        ), array("Flask" => 10.0), array()
);

Build_19::addSkill("MakeGlassPot", "Strength", "Glassware", 1.0, array(
    "Glass" => 1.0,
    "Coal" => 1.0,
        ), array("GlassPot" => 10.0), array()
);


//13. Briqueterie
Build_19::addItem("Brick", true);
Build_19::addSkill("MakeBrick", "Strength", "Brickyard", 1.0, array(
    "Clay" => 1.0,
    "Coal" => 1.0,
        ), array("Brick" => 1.0), array()
);

//14. Tisserand
Build_19::addItem("Cloth", true); //Etoffe
Build_19::addSkill("MakeEikoCloth", "Strength", "Weaver", 1.0, array(
    "EikoPlant" => 1.0,
        ), array("Cloth" => 1.0), array()
);

Build_19::addSkill("MakeSomoCloth", "Strength", "Weaver", 1.0, array(
    "SomoMoss" => 1.0,
        ), array("Cloth" => 1.0), array()
);

Build_19::addSkill("MakeBromelioCloth", "Strength", "Weaver", 1.0, array(
    "BromelioPlant" => 1.0,
        ), array("Cloth" => 1.0), array()
);

Build_19::addSkill("MakeGlove", "Strength", "Weaver", 1.0, array(
    "Cloth" => 1.0,
        ), array("Glove" => 1.0), array()
);

//15. Brasserie
Build_19::addItem("Bak", true); //Bak
Build_19::addSkill("MakeBak", "Perception", "Brewery", 1.0, array(
    "BaoFruit" => 1.0,
    "Coal" => 1.0
        ), array("Bak" => 1.0), array()
);

Build_19::addItem("AvoroBeer", true); //Biere d'Avoro
Build_19::addSkill("MakeAvoroBeer", "Perception", "Brewery", 1.0, array(
    "AvoroCereal" => 1.0,
    "Coal" => 1.0
        ), array("AvoroBeer" => 1.0), array()
);

Build_19::addItem("BeroLiqueur", true); // Liqueur d'airelles
Build_19::addSkill("MakeBeroLiqueur", "Perception", "Brewery", 1.0, array(
    "BeroFruit" => 1.0,
    "Coal" => 1.0
        ), array("BeroLiqueur" => 1.0), array()
);

Build_19::addItem("KaktoAlcohol", true); //Alcool de Kakto
Build_19::addSkill("MakeKaktoAlcohol", "Perception", "Brewery", 1.0, array(
    "KaktoJuice" => 1.0,
    "Coal" => 1.0
        ), array("KaktoAlcohol" => 1.0), array()
);

Build_19::addItem("FlentoLiqueur", true); //Alcool de Kakto
Build_19::addSkill("MakeFlentoLiqueur", "Perception", "Brewery", 1.0, array(
    "FlentoFlower" => 1.0,
    "Coal" => 1.0
        ), array("FlentoLiqueur" => 1.0), array()
);

Build_19::addItem("ThornoVodka", true); //Vodka au Thorno
Build_19::addSkill("MakeThornoVodka", "Perception", "Brewery", 1.0, array(
    "ThornoFruit" => 1.0,
    "Coal" => 1.0
        ), array("ThornoVodka" => 1.0), array()
);

Build_19::addSkill("RefineBeroJuice", "Perception", "Brewery", 1.0, array(
    "BeroJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);

Build_19::addSkill("RefineBaoJuice", "Perception", "Brewery", 1.0, array(
    "BaoJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);
Build_19::addSkill("RefineAvoroJuice", "Perception", "Brewery", 1.0, array(
    "AvoroJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);
Build_19::addSkill("RefineThornoJuice", "Perception", "Brewery", 1.0, array(
    "ThornoJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);
Build_19::addSkill("RefineKaktoJuice", "Perception", "Brewery", 1.0, array(
    "KaktoJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);

Build_19::addSkill("RefineLigioJuice", "Perception", "Brewery", 1.0, array(
    "LigioJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);

Build_19::addSkill("RefineAloeJuice", "Perception", "Brewery", 1.0, array(
    "AloeJuice" => 1.0,
    "Coal" => 1.0
        ), array("Sugar" => 1.0), array()
);