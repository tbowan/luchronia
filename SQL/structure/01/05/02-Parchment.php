<?php

class Build_01_05_02 {
    
    public static function main() {
        
        $addItem   = Build::$pdo->prepare("insert into game_ressource_item      (`name`, `groupable`) values (:name, 0)") ;
        $addParch  = Build::$pdo->prepare("insert into game_ressource_parchment (`item`, `skill`)     values (:item, :skill)") ;
        
        $getskills = Build::$pdo->prepare("select id, name, classname from game_skill_skill") ;
        $getskills->execute() ;
        
        foreach ($getskills as $row) {
            
            if ($row["name"] != "Walk" && $row["classname"] != "Primary" && $row["classname"] != "DrawMap" ) {
                $addItem->execute(array("name" => "Parchment" . $row["name"])) ;
                $item = Build::$pdo->lastInsertId() ;
                $addParch->execute(array("skill" => $row["id"], "item" => $item)) ;
            }
            
        }
        
    }
    
    
}

Build_01_05_02::main() ;