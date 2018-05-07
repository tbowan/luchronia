<?php

namespace Scripts\Item ;

class Level extends \Quantyl\Service\EnhancedService {

    public function Raz() {
        foreach (\Model\Game\Ressource\Item::GetAll() as $i) {
            $i->energy = 0 ;
            $i->update() ;
        }
    }
    
    // Primary skills
    
    public function Primary() {
        foreach (\Model\Game\Skill\Skill::getFromClassName("Primary") as $s) {
            self::OnePrimary($s) ;
        }
    }
    
    public function OnePrimary(\Model\Game\Skill\Skill $s) {
        $p = \Model\Game\Skill\Primary::GetFromSkill($s) ;
        $out = $p->out ;
        $out->energy = 1 ;
        $out->update() ;
    }
    
    // Secondary skills
    
    public function Secondary() {
        $done = 0 ;
        foreach (\Model\Game\Skill\Skill::getFromClassName("Secondary") as $s) {
            $done += self::OneSecondary($s) ;
        }
        return $done ;
    }
    
    public function OneSecondary(\Model\Game\Skill\Skill $s) {
        $max = 0 ;
        foreach ( \Model\Game\Skill\In::GetFromSkill($s) as $i ) {
            $item = $i->item ;
            if ($item->energy == 0) {
                return 0 ;
            } else if ($max < $item->energy) {
                $max = $item->energy ;
            }
        }
        $max += 1 ;
        echo "$max\t\"{$s->name}\",\n" ;
        $change = 0 ;
        foreach (\Model\Game\Skill\Out::GetFromSkill($s) as $i) {
            $item = $i->item ;
            if ($item->energy > $max || $item->energy == 0) {
                $item->energy = $max ;
                $item->update() ;
                $change ++ ;
            }
        }
        return $change ;
    }
    
    // Get View

    public function getView() {
        self::Raz() ;
        self::Primary() ;
        $done = 1 ;
        while ($done != 0) {
            $done = self::Secondary() ;
        }
    }
}
