<?php

namespace Model\Game ;

use Model\Game\City\Neighbour ;

class City extends \Quantyl\Dao\BddObject {
    
    use \Model\Name {
        \Model\Name::getName as Name_getName ;
    }
    
    public static function getNameField() {
        return "name" ;
    }
    
    public function getSlotCount() {
        $res = 1 ;
        foreach ($this->getTownHalls() as $i) {
            $l = $i->level ;
            $res += $l * ($l+1) / 2 ;
        }
        
        return $res ;
    }
    
    public function getSlotUsed() {
        return self::getCount(""
                . " select sum(slot) as s"
                . " from"
                . "     `" . Building\Instance::getTableName() . "` as instance,"
                . "     `" . Building\Type::getTableName(). "` as type"
                . " where"
                . "     type.id = instance.type and"
                . "     instance.city = :city and"
                . "     instance.health > 0",
                array("city" => $this->id),
                "s") ;
    }
    
    public function getTownHalls() {
        return Building\Instance::GetFromCityAndJob($this, Building\Job::GetByName("TownHall")) ;
    }
    
    public function hasTownHall() {
        return Building\Instance::HasCityJob($this, Building\Job::GetByName("TownHall")) ;
    }
    
    public function hasRuin() {
        return Building\Instance::HasCityJob($this, Building\Job::GetByName("Ruin")) ;
    }
    
    public function hasFriend(Character $c) {
        $row = self::getSingleRow(""
                . " select true"
                . " from"
                . "     `" . Character::getTableName() . "` as c,"
                . "     `" . Social\Friend::getTableName() . "` as f"
                . " where"
                . "     c.id = f.b and"
                . "     f.a = :char and"
                . "     c.position = :city"
                . "",
                array(
                    "char" => $c->id,
                    "city" => $this->id
                )) ;
        return $row !== false ;
    }
    
    public function canEnter(Character $character) {
        $wall = Building\Wall::GetFromCity($this) ;
        return $wall == null || $wall->canEnter($character) ;
    }
    
    public function getTownHallNames() {
        $names = array() ;
        foreach ($this->getTownHalls() as $inst) {
            $th = Building\TownHall::GetFromInstance($inst) ;
            $names[] = $th->name ;
        }
        return implode(", ", $names) ;
    }
    
    public function getAtlasName() {
        $p = $this->Name_getName() ;
        if ($p != null) {
            return $p ;
        } else {
            return $this->getGeoCoord() ;
        }
    }
    
    public function getName() {
        $n1 = $this->getTownHallNames() ;
        $n2 = $this->getAtlasName() ;
        
        if ($n1 == "") {
            return $n2 ;
        } else {
            return $n1 ;
        }
        
    }
    
    public function addCredits($amount) {
        $this->credits += $amount ;
        $this->update() ;
    }
    
    public function getCoordinate() {
        return \Quantyl\Misc\Vertex3D::XYZ($this->x, $this->y, $this->z) ;
    }

    public function getGeoCoord() {
        $res = "" ;
        if ($this->latitude >= 0) {
            $res .= \I18n::COORD_LAT_NORTH($this->latitude) ;
        } else {
            $res .= \I18n::COORD_LAT_SOUTH(- $this->latitude) ;
        }

        $res .= ", " ;
        if ($this->longitude >= 0) {
            $res .= \I18n::COORD_LONG_EAST($this->longitude) ;
        } else {
            $res .= \I18n::COORD_LONG_WEST(- $this->longitude) ;
        }

        return $res ;
    }
    
    public function setCoordinate(\Quantyl\Misc\Vertex3D $v) {
        $this->x = $v->x() ;
        $this->y = $v->y() ;
        $this->z = $v->z() ;
        
        $this->latitude  = $v->lattitude(true) ;
        $this->longitude = $v->longitude(true) ;
    }
    
    public static function GetFirst(World $w) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `world` = :world"
                . " order by id"
                . " limit 1",
                array(
                    "world" => $w->id
                        )) ;
    }
    
    public function getNext($step = 1) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `world` = :world and"
                . "     `id`    = :id + :st"
                . " order by id"
                . " limit 1",
                array(
                    "id"    => $this->id,
                    "st"    => $step,
                    "world" => $this->world->id
                        )) ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "world" :
                return World::GetById($value) ;
            case "biome" :
                return ($value == null ? null : Biome::GetById($value)) ;
            case "citizenship" :
                return \Model\Enums\Citizenship::GetById($value) ;
            case "prefecture" :
            case "palace" :
                return ($value == null ? null : City::GetById($value)) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "world" :
            case "citizenship" :
                return $value->getId() ;
            case "biome" :
            case "prefecture" :
            case "palace" :
                return ($value == null ? null : $value->getId()) ;
            default :
                return $value ;
        }
    }
    
    public static function GetFromCoord($world, $coord) {
        return static::getSingleResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " coord = :c and"
                . " world = :w",
                array(
                    "c" => $coord,
                    "w" => $world->getId()
                        )
                ) ;
    }
    
    public static function GetInBall(World $w, \Quantyl\Misc\Vertex3D $center, $d) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` "
                . " where"
                . "  abs(x - :x) < :d and"
                . "  abs(y - :y) < :d and"
                . "  abs(z - :z) < :d and"
                . "  world = :w",
                array(
                    "x" => $center->x(),
                    "y" => $center->y(),
                    "z" => $center->z(),
                    "w" => $w->getId(),
                    "d" => $d
                        )
                ) ;
    }
    
    private static function GetClosestA(World $w, \Quantyl\Misc\Vertex3D $v) {
        return static::getSingleResult(
                "select c.id as id"
                . " from ("
                . "   select"
                . "     id,"
                . "     (x-:x) * (x-:x) +"
                . "     (y-:y) * (y-:y) +"
                . "     (z-:z) * (z-:z) as dist"
                . "   from `" . static::getTableName() . "`"
                . "   where"
                . "     world = :w"
                . "   order by dist"
                . "   limit 1"
                . "   ) as c",
                array(
                    "x" => $v->x(),
                    "y" => $v->y(),
                    "z" => $v->z(),
                    "w" => $w->getId(),
                        )
                ) ;
    }
    
    private static function GetClosestB(World $w, \Quantyl\Misc\Vertex3D $v) {
        $d = 1.0 * pi() / $w->size ;
        return static::getSingleResult(
                "select c.id as id "
                . "from ("
                . "  select"
                . "    id,"
                . "    (x-:x) * (x-:x) +"
                . "    (y-:y) * (y-:y) +"
                . "    (z-:z) * (z-:z) as dist"
                . "  from ("
                . "    select"
                . "      id, x, y, z"
                . "    from `" . static::getTableName() . "`"
                . "    where"
                . "      x > :x - :d and"
                . "      x < :x + :d and"
                . "      y > :y - :d and"
                . "      y < :y + :d and"
                . "      z > :z - :d and"
                . "      z < :z + :d and"
                . "      world = :w"
                . "    ) as t"
                . "  order by dist"
                . "  limit 1"
                . "  ) as c",
                array(
                    "x" => $v->x(),
                    "y" => $v->y(),
                    "z" => $v->z(),
                    "w" => $w->getId(),
                    "d" => $d
                        )
                ) ;
    }
    
    public static function GetClosestC(World $w, \Quantyl\Misc\Vertex3D $v, $last = null) {
        if ($last == null) {
            return self::GetClosestA($w, $v) ;
        }
        /*
        $path = clone $v ;
        $path->substract($last->getCoordinate()) ;
        $dist = $path->length() ;
        $max = 0.8 * 0.2 * pi() / $w->size ;
        if ($dist < $max) {
            return $last ;
        }
        */
        return static::getSingleResult(
                "select c.id as id"
                . " from ("
                . "   select"
                . "     city.id as id,"
                . "     (city.x-:x) * (city.x-:x) +"
                . "     (city.y-:y) * (city.y-:y) +"
                . "     (city.z-:z) * (city.z-:z) as dist"
                . "   from"
                . "     `" . static::getTableName() . "` as city,"
                . "     `" . Neighbour::getTableName() . "` as n"
                . "   where"
                . "      n.a = city.id and"
                . "     (n.a = :id or n.b = :id)"
                . "   order by dist"
                . "   limit 1"
                . "   ) as c",
                array(
                    "x" => $v->x(),
                    "y" => $v->y(),
                    "z" => $v->z(),
                    "id" => $last->getId(),
                        )
                ) ;
    }
    
    public static function GetRepatriate(Character $c) {
        
        $select = " select id, x, y, z from ("
                . " select"
                . "  gc.*,"
                . "  (x-:x) * (x-:x) +"
                . "  (y-:y) * (y-:y) +"
                . "  (z-:z) * (z-:z) as dist"
                . " from       game_city              as gc"
                . " inner join game_building_instance as gbi on gbi.city = gc.id"
                . " inner join game_building_job      as gbj on gbi.job  = gbj.id"
                . " where"
                . "    gbj.name in (\"TownHall\") and"
                . "    repatriate_allowed"
                . " order by dist"
                . " limit 10"
                . ") as temp" ;
        
        $v = $c->position->getCoordinate() ;
        $w = $c->position->world ;
        
        return static::getResult($select, array(
            "x" => $v->x(),
            "y" => $v->y(),
            "z" => $v->z(),
            "w" => $w->id
        )) ;
        
    }
    
    public function getTraversalCost() {
        $res = 1.0 ;
        $roads = Building\Instance::GetFromCityAndJob($this, Building\Job::GetByName("Road")) ;
        foreach ($roads as $r) {
            $l = $r->level ;
            $res += $l * ($l + 1) / 2 ;
        }
        return $res ;
    }
    
    public static function GetPathCost($path) {
        $cost = 0 ;
        $prev = null ;
        foreach ($path as $city) {
            if ($prev != null) {
                // check neightbours
                $neigh = \Model\Game\City\Neighbour::getFromAB($prev, $city) ;
                if ($neigh === null) {
                    return -1 ;
                } else {
                    $cost += $neigh->GetPathCost() ;
                }
            } else {
                $prev = $city ;
            }
        }
        return $cost ;
    }
    
    public static function GetDist(City $a, City $b) {
        if ($a->equals($b)) {
            return 0.0 ;
        } else {
            $p1 = $a->getCoordinate() ;
            $p2 = $b->getCoordinate() ;

            $scalar = $p1->ScalarProduct($p2) ;
            $angle = acos($scalar) ;
            // rayon equatorial de la lune
            return round(1736.0 * $angle, 2) ;
        }
    }
    
    public static function GetClosest(World $w, \Quantyl\Misc\Vertex3D $v, $version = 1, $last = null) {
        $v->normalize() ;
        switch($version) {
            case 3 :
                $prev = $last ;
                $next = self::GetClosestC($w, $v, $prev) ;
                while (! $prev->equals($next)) {
                    $prev = $next ;
                    $next = self::GetClosestC($w, $v, $prev) ;
                }
                return $prev ;
            case 2 :
                return self::GetClosestB($w, $v) ;
            case 1 :
            default:
                return self::GetClosestA($w, $v) ;
        }
    }
    
    public static function GetFromPrefecture(City $prefecture) {
        return static::getResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where prefecture = :p",
                array("p" => $prefecture->getId())
                ) ;
    }
    
    public static function GetFromWorld($world, $n = 1, $k = 0) {
        return static::getResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " world = :w and"
                . " id % :n = :k",
                array(
                    "w" => $world->getId(),
                    "n" => $n,
                    "k" => $k
                        )
                ) ;
    }
    
    
    
}
