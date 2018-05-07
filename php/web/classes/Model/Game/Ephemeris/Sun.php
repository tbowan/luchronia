<?php

namespace Model\Game\Ephemeris ;

class Sun extends \Quantyl\Dao\BddObject {

    public function getPos() {
        return \Quantyl\Misc\Vertex3D::XYZ(
                $this->x,
                $this->y,
                $this->z
                ) ;
    }
    
    public static function GetLastBefore($time) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `time` <= :time"
                . " order by `time` desc"
                . " limit 1",
                array("time" => $time)) ;
    }
    
    public static function GetFirstAfter($time) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `time` > :time"
                . " order by `time`"
                . " limit 1",
                array("time" => $time)) ;
    }
    
    public static function InterpolateWithPos($t, \Quantyl\Misc\Vertex3D $a, \Quantyl\Misc\Vertex3D $b) {
        $tb = 1.0 - $t ;
        return \Quantyl\Misc\Vertex3D::XYZ(
                $a->x() * $tb + $b->x() * $t,
                $a->y() * $tb + $b->y() * $t,
                $a->z() * $tb + $b->z() * $t
                ) ;
    }
    
    public static function InterpolateWithSun($time, Sun $a, Sun $b) {
        $dt = $b->time - $a->time ;
        $t = ($time - $a->time) / $dt ;
        return self::InterpolateWithPos($t, $a->getPos(), $b->getPos()) ;
    }
    
    public static function GetPosByTime($time) {
        
        $before = static::GetLastBefore($time) ;
        $after  = static::GetFirstAfter($time) ;
        
        return static::InterpolateWithSun($time, $before, $after) ;
        
    }
    
    public static function GetAfter($time) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `time` >= :time"
                . " order by `time`",
                array("time" => $time)) ;
    }
    
    public static function GetSunChange(\Quantyl\Misc\Vertex3D $pos, $before, Sun $after, $d) {
        if ($before == null) {
            $before  = self::GetLastBefore($after->time - 1) ;
        }
        
        $a = $before->getPos() ;
        $b = $after->getPos() ;
        $dt = 3600 / ($after->time - $before->time) ;
        
        for ($t = 0; $t <= 1.0; $t += $dt) {
            $sunpos = self::InterpolateWithPos($t, $a, $b) ;
            $s = $pos->ScalarProduct($sunpos) ;
            if ($s * $d > 0.0) {
                return $before->time + $t * ($after->time - $before->time) ;
            }
        }
        return $after->time ;
    }
    
    public static function GetSunSet(\Quantyl\Misc\Vertex3D $pos, $sunrise) {
        $prev = 0.0 ;
        $before = null ;
        foreach (self::GetAfter($sunrise) as $ephemeris) {
            $sunpos = $ephemeris->getPos() ;
            $now = $sunpos->ScalarProduct($pos) ;
            if ($now < 0.0 && $prev > 0.0) {
                return self::GetSunChange($pos, $before, $ephemeris, -1) ;
            }
            $before = $ephemeris ;
            $prev = $now ;
        }
        // Should never occurs as there are ephemeris until December 2015
        return 0 ;
    }
    
    public static function GetSunRise(\Quantyl\Misc\Vertex3D $pos, $sunset) {
        $prev = 0.0 ;
        $before = null ;
        foreach (self::GetAfter($sunset) as $ephemeris) {
            $sunpos = $ephemeris->getPos() ;
            $now = $sunpos->ScalarProduct($pos) ;
            if ($now > 0.0 && $prev < 0.0) {
                return self::GetSunChange($pos, $before, $ephemeris, +1) ;
            }
            $before = $ephemeris ;
            $prev = $now ;
        }
        // Should never occurs as there are ephemeris until December 2016
        return 0 ;
    }
}
