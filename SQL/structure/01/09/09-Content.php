<?php

class Build_010909 {
    
    private static $pdo ;
    
    private static $insert_item ;
    private static $insert_tool ;
    private static $insert_munition ;
    
    public static function init() {
        
        self::$pdo = Build::$pdo ;
        
        self::$insert_item = self::$pdo->prepare(""
                . "insert into game_ressource_item "
                . "(`name`, `groupable`, `energy`) "
                . "values "
                . "(:name , :groupable , :energy)") ;
        
        self::$insert_tool = self::$pdo->prepare(""
                . "insert into game_skill_tool "
                . "(`skill`, `item`, `coef`) "
                . "select "
                . "     skill.id    as `skill`,"
                . "     item.id     as `item`,"
                . "     :coef       as `coef`"
                . " from"
                . "     game_skill_skill    as skill,"
                . "     game_ressource_item as item"
                . " where"
                . "     skill.name = :skill and"
                . "     item.name  = :item") ;
        
        self::$insert_munition = self::$pdo->prepare(""
                . "insert into game_ressource_munition "
                . "(`weapon`, `munition`, `amount`, `coef`) "
                . "select "
                . "     weapon.id   as `weapon`,"
                . "     munition.id as `munition`,"
                . "     :amount     as `amount`,"
                . "     :coef       as `coef`"
                . " from"
                . "     game_ressource_item as weapon,"
                . "     game_ressource_item as munition"
                . " where"
                . "     weapon.name     = :weapon and"
                . "     munition.name   = :munition") ;
    }
    
    public static function addItem($name, $groupable, $energy) {
        self::$insert_item->execute(array(
            "name"      => $name,
            "groupable" => $groupable,
            "energy"    => $energy
        )) ;
        return self::$pdo->lastInsertId() ;
    }
    
    public static function addTool($item, $skill, $coef) {
        self::$insert_tool->execute(array(
            "skill" => $skill,
            "item"  => $item,
            "coef"  => $coef
        )) ;
        return self::$pdo->lastInsertId() ;
    }
    
    public static function addMunition($weapon, $munition, $amount, $coef) {
        self::$insert_munition->execute(array(
            "weapon"    => $weapon,
            "munition"  => $munition,
            "amount"    => $amount,
            "coef"      => $coef,
        )) ;
        return self::$pdo->lastInsertId() ;
    }
    
    public static function addWeapon($name, $skill, $energy = 1000) {
        $wid = self::addItem($name, 0, $energy) ;
        $tid = self::addTool($name, $skill, 1.0) ;
    }
    
    
    
}

Build_010909::init() ;

/*
 * Tir à l'arc
 */
Build_010909::addWeapon("SmallBow", "Archery") ;
Build_010909::addWeapon("Bow",      "Archery") ;
Build_010909::addWeapon("LongBow",  "Archery") ;
Build_010909::addWeapon("Yumi",     "Archery") ;


Build_010909::addItem("SmallArrowWood",     0, 10) ;
Build_010909::addItem("SmallArrowSteel",    0, 15) ;
Build_010909::addItem("BowArrowWood",       0, 20) ;
Build_010909::addItem("BowArrowSteel",      0, 30) ;
Build_010909::addItem("LongBowWood",        0, 40) ;
Build_010909::addItem("LongBowSteel",       0, 60) ;
Build_010909::addItem("YumiArrowWood",      0, 40) ;
Build_010909::addItem("YumiArrowSteel",     0, 60) ;

Build_010909::addMunition("SmallBow",   "SmallArrowWood",   1.0, 1.0) ;
Build_010909::addMunition("SmallBow",   "SmallArrowSteel",  1.0, 1.0) ;
Build_010909::addMunition("Bow",        "BowArrowWood",     1.0, 1.0) ;
Build_010909::addMunition("Bow",        "BowArrowSteel",    1.0, 1.0) ;
Build_010909::addMunition("LongBow",    "LongBowWood",      1.0, 1.0) ;
Build_010909::addMunition("LongBow",    "LongBowSteel",     1.0, 1.0) ;
Build_010909::addMunition("Yumi",       "YumiArrowWood",    1.0, 1.0) ;
Build_010909::addMunition("Yumi",       "YumiArrowSteel",   1.0, 1.0) ;


/*
 * Arbalette
 */
Build_010909::addWeapon("LeverCrossbow",        "Crossbow") ;
Build_010909::addWeapon("Cranequin",            "Crossbow", 2000) ;
Build_010909::addWeapon("Windlass",             "Crossbow", 3000) ;
Build_010909::addWeapon("RepeatingCrossbow",    "Crossbow") ;

Build_010909::addItem("QuarrekWood",     0, 40) ;
Build_010909::addItem("QuarrekSteel",    0, 60) ;

Build_010909::addMunition("LeverCrossbow",      "QuarrekWood",   1.0, 1.0) ;
Build_010909::addMunition("LeverCrossbow",      "QuarrekSteel",  1.0, 1.0) ;
Build_010909::addMunition("Cranequin",          "QuarrekWood",   1.0, 1.0) ;
Build_010909::addMunition("Cranequin",          "QuarrekSteel",  1.0, 1.0) ;
Build_010909::addMunition("Windlass",           "QuarrekWood",   1.0, 1.0) ;
Build_010909::addMunition("Windlass",           "QuarrekSteel",  1.0, 1.0) ;
Build_010909::addMunition("RepeatingCrossbow",  "QuarrekWood",   3.0, 1.0) ;
Build_010909::addMunition("RepeatingCrossbow",  "QuarrekSteel",  3.0, 1.0) ;

/*
 * Pistolet
 */
Build_010909::addWeapon("LefaucheuxRevolver",       "Pistol") ;
Build_010909::addWeapon("PinfirePistol",            "Pistol") ;
Build_010909::addWeapon("BentleyRevolver1855",      "Pistol") ;
Build_010909::addWeapon("PerrinRevolver1859",       "Pistol") ;
Build_010909::addWeapon("PondRevolver",             "Pistol") ;
Build_010909::addWeapon("BeaumontAdams1866",        "Pistol") ;
Build_010909::addWeapon("ChamelotDelvigne1873",     "Pistol") ;
Build_010909::addWeapon("ZigZagMauser",             "Pistol") ;
Build_010909::addWeapon("BritishBulldogRevolver",   "Pistol") ;
Build_010909::addWeapon("Webley445",                "Pistol") ;
Build_010909::addWeapon("ServicePistol1892",        "Pistol") ;

Build_010909::addItem("BulletPistol-m-4.5", 0, 5) ;
Build_010909::addItem("BulletPistol-m-8",   0, 8) ;
Build_010909::addItem("BulletPistol-m-9",   0, 9) ;
Build_010909::addItem("BulletPistol-m-10",  0, 10) ;
Build_010909::addItem("BulletPistol-m-11",  0, 11) ;
Build_010909::addItem("BulletPistol-m-12",  0, 12) ;
Build_010909::addItem("BulletPistol-p-32",  0, 8) ;
Build_010909::addItem("BulletPistol-p-45",  0, 11) ;
Build_010909::addItem("BulletPistol-p-455", 0, 12) ;

Build_010909::addMunition("LefaucheuxRevolver",     "BulletPistol-m-11",    6.0, 1.0) ;
Build_010909::addMunition("PinfirePistol",          "BulletPistol-m-4.5",   6.0, 1.0) ;
Build_010909::addMunition("BentleyRevolver1855",    "BulletPistol-m-10",    6.0, 1.0) ;
Build_010909::addMunition("PerrinRevolver1859",     "BulletPistol-m-12",    5.0, 1.0) ;
Build_010909::addMunition("PondRevolver",           "BulletPistol-p-32",    6.0, 1.0) ;
Build_010909::addMunition("BeaumontAdams1866",      "BulletPistol-p-45",    5.0, 1.0) ;
Build_010909::addMunition("ChamelotDelvigne1873",   "BulletPistol-m-11",    6.0, 1.0) ;
Build_010909::addMunition("ZigZagMauser",           "BulletPistol-m-9",     6.0, 1.0) ;
Build_010909::addMunition("BritishBulldogRevolver", "BulletPistol-m-11",    5.0, 1.0) ;
Build_010909::addMunition("Webley445",              "BulletPistol-p-455",   6.0, 1.0) ;
Build_010909::addMunition("ServicePistol1892",      "BulletPistol-m-8",     6.0, 1.0) ;

/*
 * Fusils
 */
Build_010909::addWeapon("DreyseRifle1849",      "Rifle") ;
Build_010909::addWeapon("RemingtonRollingBlock","Rifle") ;
Build_010909::addWeapon("SniderEnfieldMarkI",   "Rifle") ;
Build_010909::addWeapon("Spencer52",            "Rifle") ;
Build_010909::addWeapon("ChassepotRifle",       "Rifle") ;
Build_010909::addWeapon("SnuffboxRifle",        "Rifle") ;
Build_010909::addWeapon("Krnka1867",            "Rifle") ;
Build_010909::addWeapon("MartiniHenryM1871",    "Rifle") ;
Build_010909::addWeapon("BelgiumComblainM1882", "Rifle") ;
Build_010909::addWeapon("HuntingGun1840",       "Rifle") ;
Build_010909::addWeapon("BelgiumHuntingGun",    "Rifle") ;

// Munitions pistolets
Build_010909::addMunition("ChassepotRifle",         "BulletPistol-m-11",    1.0, 1.0) ;
Build_010909::addMunition("MartiniHenryM1871",      "BulletPistol-p-45",    1.0, 1.0) ;
Build_010909::addMunition("BelgiumComblainM1882",   "BulletPistol-m-11",    1.0, 1.0) ;

// Munitions fulils
Build_010909::addItem("BulletRifle-m-11",       0, 66) ;
Build_010909::addItem("BulletRifle-m-15.24",    0, 92) ;
Build_010909::addItem("BulletRifle-m-15.43",    0, 93) ;
Build_010909::addItem("BulletRifle-m-18.2",     0, 110) ;
Build_010909::addItem("BulletRifle-p-45",       0, 66) ;
Build_010909::addItem("BulletRifle-p-50",       0, 75) ;
Build_010909::addItem("BulletRifle-p-52",       0, 80) ;
Build_010909::addItem("BulletRifle-p-577",      0, 85) ;
Build_010909::addItem("BulletRifle-c-12",       0, 110) ;
Build_010909::addItem("BulletRifle-c-8",        0, 130) ;

Build_010909::addMunition("DreyseRifle1849",        "BulletRifle-m-15.43",  1.0, 1.0) ;
Build_010909::addMunition("RemingtonRollingBlock",  "BulletRifle-p-50",     1.0, 1.0) ;
Build_010909::addMunition("SniderEnfieldMarkI",     "BulletRifle-p-577",    1.0, 1.0) ;
Build_010909::addMunition("Spencer52",              "BulletRifle-p-52",     7.0, 1.0) ;
Build_010909::addMunition("ChassepotRifle",         "BulletRifle-m-11",     1.0, 1.0) ;
Build_010909::addMunition("SnuffboxRifle",          "BulletRifle-m-18.2",   1.0, 1.0) ;
Build_010909::addMunition("Krnka1867",              "BulletRifle-m-15.24",  1.0, 1.0) ;
Build_010909::addMunition("MartiniHenryM1871",      "BulletRifle-p-45",     1.0, 1.0) ;
Build_010909::addMunition("BelgiumComblainM1882",   "BulletRifle-m-11",     1.0, 1.0) ;
Build_010909::addMunition("HuntingGun1840",         "BulletRifle-c-12",     1.0, 1.0) ;
Build_010909::addMunition("BelgiumHuntingGun",      "BulletRifle-c-8",      1.0, 1.0) ;

/*
 * Baïonette
 */

Build_010909::addTool("Spencer52",      "Bayonet", 1.0) ;
Build_010909::addTool("ChassepotRifle", "Bayonet", 1.0) ;
Build_010909::addTool("SnuffboxRifle",  "Bayonet", 1.0) ;
Build_010909::addTool("Krnka1867",      "Bayonet", 1.0) ;

/*
 * Cane
 */
Build_010909::addWeapon("WalkingStick","Cane") ;
Build_010909::addWeapon("CaneToise","Cane") ;
Build_010909::addWeapon("DaggerCane","Cane") ;
Build_010909::addWeapon("SwordCane","Cane") ;
Build_010909::addWeapon("ClubCane","Cane") ;

/*
 * Fleuret
 */
Build_010909::addWeapon("GendarmerieSword","Foil") ;
Build_010909::addWeapon("CourtSword","Foil") ;

/*
 * épée
 */
Build_010909::addWeapon("DiplomaticCorpsSword","Sword") ;
Build_010909::addWeapon("JuniorEmpireSword","Sword") ;

/*
 * Sabre
 */
Build_010909::addWeapon("EnglishRidingSabre","Saber") ;
Build_010909::addWeapon("InfantrySabre","Saber") ;
Build_010909::addWeapon("Cutlass","Saber") ;
