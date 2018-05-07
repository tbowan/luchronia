<?php

class Build_18 {
    
    
    private static $slots ;
    private static $insert_slot ;

    private static $insert_equipable ;
    
    private static $get_item ;
    private static $_items ;

    
    public static function init() {
        
        self::$insert_slot = Build::$pdo->prepare(
                "insert into game_ressource_slot"
                . "    (`name`, `amount`)"
                . "  VALUES"
                . "    (:name,   :amount)"
                ) ;

        self::$insert_equipable = Build::$pdo->prepare(
                "insert into game_ressource_equipable"
                . "    (`item`, `slot`, `amount`, `race`, `sex`)"
                . "  VALUES"
                . "    (:item,  :slot,  :amount,  :race,  :sex )"
                ) ;
        
        self::$get_item =  Build::$pdo->prepare(
                "select id from game_ressource_item where `name` = :name"
                ) ;
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

    public static function AddSlot($name, $amount) {
        return self::_insert(self::$insert_slot, array("name" => $name, "amount" => $amount)) ;
    }
    
    public static function AddEquipable($item, $slot, $amount) {
        return self::_insert(self::$insert_equipable, array (
            "item"   => self::_getItem($item),
            "slot"   => $slot,
            "amount" => $amount,
            "race"   => null,
            "sex"    => null
        )) ;
    }
}

Build_18::init() ;

$hand   = Build_18::AddSlot("Hand", 2) ;
$gloves = Build_18::AddSlot("Gloves", 1) ;

Build_18::AddEquipable("SawOneHand",    $hand,   1) ;
Build_18::AddEquipable("Saw",           $hand,   2) ;
Build_18::AddEquipable("Axe",           $hand,   2) ;
Build_18::AddEquipable("Serpe",         $hand,   1) ;
Build_18::AddEquipable("Secateur",      $hand,   1) ;
Build_18::AddEquipable("Scythe",        $hand,   2) ;
Build_18::AddEquipable("Knife",         $hand,   1) ;
Build_18::AddEquipable("GathererKnife", $hand,   1) ;
Build_18::AddEquipable("Glove",         $gloves, 2) ;
Build_18::AddEquipable("Comb",          $hand,   1) ;
Build_18::AddEquipable("Shovel",        $hand,   2) ;
Build_18::AddEquipable("Pickaxe",       $hand,   2) ;
Build_18::AddEquipable("Dynamite",      $hand,   1) ;
Build_18::AddEquipable("SealLiquid",    $hand,   1) ;
Build_18::AddEquipable("Gourd",         $hand,   1) ;