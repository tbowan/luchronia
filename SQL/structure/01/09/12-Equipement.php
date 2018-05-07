<?php


class Build_010911 {
    
    private static $pdo ;
    
    private static $insert_equipable ;
    
    public static function init() {
        
        self::$pdo = Build::$pdo ;
        
        self::$insert_equipable = self::$pdo->prepare(""
                . "insert into game_ressource_equipable "
                . "     (`item`, `slot`, `amount`, `race`, `sex`, `discretion`, `defense`, `resistance`, `impact`) "
                . " select"
                . "     item.id as `item`,"
                . "     slot.id as `slot`,"
                . "     :amount as `amount`,"
                . "     0 as `race`,"
                . "     0 as `sex`,"
                . "     :discretion as `discretion`,"
                . "     :defense as `defense`,"
                . "     :resistance as `resistance`,"
                . "     :impact as `impact`"
                . " from"
                . "     game_ressource_item as item,"
                . "     game_ressource_slot as slot"
                . " where"
                . "     item.name = :item and"
                . "     slot.name = :slot"
                . "") ;
    }
    
    public static function addEquipement($item, $slot, $amount, $discretion, $defense, $resistance, $impact) {
        self::$insert_equipable->execute(array(
            "item"          => $item,
            "slot"          => $slot,
            "amount"        => $amount,
            "discretion"    => $discretion,
            "defense"       => $defense,
            "resistance"    => $resistance,
            "impact"        => $impact
        )) ;
        return self::$pdo->lastInsertId() ;
    }
    
}

Build_010911::init() ;

/*
 * Weapons - Archery
 */
Build_010911::addEquipement("SmallBow", "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("Bow",      "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("LongBow",  "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("Yumi",     "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;

/*
 * Weapons - Crossbow
 */
Build_010911::addEquipement("LeverCrossbow",        "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("Cranequin",            "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("Windlass",             "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("RepeatingCrossbow",    "Hand", 2, 0.0, 1.0, 0.0, 0.0) ;

/*
 * Weapons - Pistols
 */
Build_010911::addEquipement("LefaucheuxRevolver",       "Hand", 1, -2.0, 1.0, 2.0, 0.0) ;
Build_010911::addEquipement("PinfirePistol",            "Hand", 1,  0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("BentleyRevolver1855",      "Hand", 1, -1.5, 1.0, 1.5, 0.0) ;
Build_010911::addEquipement("PerrinRevolver1859",       "Hand", 1, -2.5, 1.0, 2.5, 0.0) ;
Build_010911::addEquipement("PondRevolver",             "Hand", 1, -0.5, 1.0, 0.5, 0.0) ;
Build_010911::addEquipement("BeaumontAdams1866",        "Hand", 1, -2.0, 1.0, 2.0, 0.0) ;
Build_010911::addEquipement("ChamelotDelvigne1873",     "Hand", 1, -2.0, 1.0, 2.0, 0.0) ;
Build_010911::addEquipement("ZigZagMauser",             "Hand", 1, -1.0, 1.0, 1.0, 0.0) ;
Build_010911::addEquipement("BritishBulldogRevolver",   "Hand", 1, -2.0, 1.0, 2.0, 0.0) ;
Build_010911::addEquipement("Webley445",                "Hand", 1, -2.0, 1.0, 2.0, 0.0) ;
Build_010911::addEquipement("ServicePistol1892",        "Hand", 1, -0.5, 1.0, 0.5, 0.0) ;

/*
 * Weapons - Rifles
 */
Build_010911::addEquipement("DreyseRifle1849",          "Hand", 2, -3.0, 1.0, 0.0, 3.0) ;
Build_010911::addEquipement("RemingtonRollingBlock",    "Hand", 2, -2.5, 1.0, 0.0, 2.5) ;
Build_010911::addEquipement("SniderEnfieldMarkI",       "Hand", 2, -2.8, 1.0, 0.0, 2.8) ;
Build_010911::addEquipement("Spencer52",                "Hand", 2, -2.8, 1.0, 0.0, 2.8) ;
Build_010911::addEquipement("ChassepotRifle",           "Hand", 2, -2.0, 1.0, 0.0, 2.0) ;
Build_010911::addEquipement("SnuffboxRifle",            "Hand", 2, -4.0, 1.0, 0.0, 4.0) ;
Build_010911::addEquipement("Krnka1867",                "Hand", 2, -3.0, 1.0, 0.0, 3.0) ;
Build_010911::addEquipement("MartiniHenryM1871",        "Hand", 2, -2.0, 1.0, 0.0, 2.0) ;
Build_010911::addEquipement("BelgiumComblainM1882",     "Hand", 2, -2.0, 1.0, 0.0, 2.0) ;
Build_010911::addEquipement("HuntingGun1840",           "Hand", 2, -2.5, 1.0, 0.0, 2.5) ;
Build_010911::addEquipement("BelgiumHuntingGun",        "Hand", 2, -1.0, 1.0, 0.0, 1.0) ;

/*
 * Weapons - Canes
 */
Build_010911::addEquipement("WalkingStick", "Hand", 1, 0.0, 2.0, 0.0, 0.0) ;
Build_010911::addEquipement("CaneToise",    "Hand", 1, 0.0, 2.0, 0.0, 0.0) ;
Build_010911::addEquipement("DaggerCane",   "Hand", 1, 0.0, 2.0, 0.0, 0.0) ;
Build_010911::addEquipement("SwordCane",    "Hand", 1, 0.0, 2.0, 0.0, 0.0) ;
Build_010911::addEquipement("ClubCane",     "Hand", 1, 0.0, 2.0, 0.0, 0.0) ;

/*
 * Weapons - Foil
 */
Build_010911::addEquipement("GendarmerieSword", "Hand", 1, 0.0, 1.0, 0.0, 0.0) ;
Build_010911::addEquipement("CourtSword",       "Hand", 1, 0.0, 1.0, 0.0, 0.0) ;

/*
 * Weapons - Sword
 */
Build_010911::addEquipement("DiplomaticCorpsSword", "Hand", 1, 0.0, 3.0, 0.0, 0.0) ;
Build_010911::addEquipement("JuniorEmpireSword",    "Hand", 1, 0.0, 3.0, 0.0, 0.0) ;

/*
 * Weapons - Saber
 */
Build_010911::addEquipement("EnglishRidingSabre",   "Hand", 1,  0.0, 2.0, 0.0, 0.0) ;
Build_010911::addEquipement("InfantrySabre",        "Hand", 1,  0.0, 2.0, 0.0, 0.0) ;
Build_010911::addEquipement("Cutlass",              "Hand", 1, -1.0, 3.0, 0.0, 0.0) ;

