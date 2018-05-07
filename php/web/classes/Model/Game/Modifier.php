<?php

namespace Model\Game ;

class Modifier extends \Quantyl\Dao\BddObject {
    
    use \Model\DescriptionTranslated ;
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "position" :
                return ($value == null ? null : \Model\Game\City::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "position" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    public function giveTo(Character $c) {
        
        $c->addTime($this->time) ;
        $c->addExperience($this->experience) ;
        $c->gainLevel($this->level) ;
        $c->feed($this->energy, $this->hydration) ;
        $c->health += $this->health ;
        if ($this->position != null) {
            $c->position = $this->position ;
        }
        
        $c->update() ;
        
        if ($this->duration == -1) {
            Character\Modifier::createFromValues(array(
                "modifier" => $this,
                "character" => $c,
                "until" => 0
            )) ;
        } else if ($this->duration > 0) {
            Character\Modifier::createFromValues(array(
                "modifier" => $this,
                "character" => $c,
                "until" => time() + $this->duration
            )) ;
        }
        
    }

    public static function getNameField() {
        return "name" ;
    }

    public static function getDescriptionPrefix() {
        return "MODIFIER_DESCRIPTION_" ;
    }

    public static function getNamePrefix() {
        return "MODIFIER_NAME_" ;
    }

}
