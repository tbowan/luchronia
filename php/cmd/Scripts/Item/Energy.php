<?php

namespace Scripts\Item ;

class Energy extends \Quantyl\Service\EnhancedService {
    
    public static $_base = 99 ;
    public static $_epsilon = 1 ;
    
    public function setEnergy($skill, $item, $new) {
        if ($new != 0 && ($item->energy == 0 || $new < ($item->energy - self::$_epsilon))) {
            echo " - " . $skill->getName() . " - " . $item->getName() . " : " . $item->energy . " => " . $new . "\n" ;
            $item->energy = $new ;
            $item->update() ;
            return 1 ;
        } else {
            return 0 ;
        }

    }
    
    public function AllSkills() {
        $done = 0 ;
        foreach (\Model\Game\Skill\Skill::GetAll() as $skill) {
            // echo " - " . $skill->getName() . " / " . $skill->classname . "\n" ;
            $fcn = $skill->classname ;
            $done += $this->$fcn($skill) ;
        }
        return $done ;
    }
    
    public function Barricade($skill) { return 0 ; }
    public function Build($skill) { return 0 ; }
    public function Digout($skill) { return 0 ; }
    public function Fight($skill) { return 0 ; }
    public function Learn($skill) { return 0 ; }
    public function Move($skill) { return 0 ; }
    public function Research($skill) { return 0 ; }
    public function Search($skill) { return 0 ; }
    public function StudyBuilding($skill) { return 0 ; }
    public function StudyMap($skill) { return 0 ; }
    public function Teach($skill) { return 0 ; }
    public function Heal($skill) { return 0 ; }

    public function ProspectGround($skill) { return 0 ; }
    public function ProspectUnderground($skill) { return 0 ; }
    public function ProspectArcheo($skill) { return 0 ; }

    
    public function DrawMap($skill) {
        $done = 0 ;
        foreach (\Model\Game\Building\Map::getFromSkill($skill) as $map) {
            $done += $this->setEnergy($skill, $map->item, self::$_base * $map->tech) ;
        }
        return $done ;
    }

    public function FieldGather($skill) {
        $fs = \Model\Game\Skill\Field::GetFromSkill($skill) ;
        $item = $fs->gain ;
        return $this->setEnergy($skill, $item, self::$_base) ;
    }
    
    
    public function Primary($skill) {
        $fs = \Model\Game\Skill\Primary::GetFromSkill($skill) ;
        $item = $fs->out ;
        return $this->setEnergy($skill, $item, self::$_base / $fs->coef) ;
    }
    
    public function Secondary($skill) {
        $cost = self::$_base ;
        foreach (\Model\Game\Skill\In::GetFromSkill($skill) as $in) {
            if ($in->item->energy == 0) {
                return 0 ;
            } else {
                $cost += $in->item->energy * $in->amount ;
            }
        }
        
        $done = 0 ;
        foreach (\Model\Game\Skill\Out::GetFromSkill($skill) as $out) {
            $done += $this->setEnergy($skill, $out->item, $cost / $out->amount ) ;
        }
        return $done ;
    }
    
    public function getCoefs($skill) {
        $cfs = array() ;
        if ($skill->by_hand != null) {
            $cfs[] = array($skill->by_hand, 0) ;
        }
        foreach (\Model\Game\Skill\Tool::GetFromSkill($skill) as $t) {
            if ($t->item->energy != 0) {
                $cfs[] = array($t->coef, $t->item->energy / 100) ;
            }
        }
        return $cfs ;
    }
    
    public function raz() {
        echo "Raz ...\n" ;
        foreach (\Model\Game\Ressource\Item::GetAll() as $i) {
            $i->energy = 0 ;
            $i->update() ;
        }
        echo " - Done\n" ;
    }
    
    public function hack() {
        
        echo "Hack ...\n" ;
        
        // Outils génériques, ne sont pas encore constructibles
        $tobedone = array(
            "ToolWoodCutter" => 1000.0,
            "ToolGatherer" => 1000.0,
            "ToolReaper" => 1000.0,
            "ToolHerbalist" => 1000.0,
            "ToolCarrier" => 1000.0,
            "ToolMiner" => 1000.0,
            "ToolWellDigger" => 1000.0,
            "ToolFarmer" => 1000.0,
            "ToolCreamer" => 1000.0,
            "ToolConfectioner" => 1000.0,
            "ToolPastryMaker" => 1000.0,
            "ToolCook" => 1000.0,
            "ToolWoodSawyer" => 1000.0,
            "ToolWoodTurner" => 1000.0,
            "ToolCarpenter" => 1000.0,
            "ToolCoal" => 1000.0,
            "ToolSteelMaker" => 1000.0,
            "ToolSMith" => 1000.0,
            "ToolDryer" => 1000.0,
            "ToolBasketMaker" => 1000.0,
            "ToolMiller" => 1000.0,
            "ToolGlassMaker" => 1000.0,
            "ToolBrickMaker" => 1000.0,
            "ToolWeaver" => 1000.0,
            "ToolTailor" => 1000.0,
            "ToolBrewer" => 1000.0,
            "ToolRefiner" => 1000.0,
            "ToolDoctor" => 1000.0,
            "ToolArchitect" => 1000.0,
            "ToolBuilder" => 1000.0,
            "ToolArchaeologist" => 1000.0,
            "ToolExplorer" => 1000.0,
            "ToolPharmacist" => 1000.0,
        ) ;
        
        foreach ($tobedone as $name => $energy) {
            $item = \Model\Game\Ressource\Item::GetByName($name) ;
            $item->energy = $energy ;
            $item->update() ;
            
            echo " - " . $item->getName() . " : " . $energy . "\n" ;
        }
    }
    
    public function getView() {
        $this->raz() ;
        $this->hack() ;
        $done = 1 ;
        while ($done > 0) {
            echo "All Skills ...\n" ;
            $done = $this->AllSkills() ;
            echo " - Done : $done\n" ;
        }
    }
    
}
