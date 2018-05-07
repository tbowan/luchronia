<?php

namespace Model\Game  ;

class Character extends \Quantyl\Dao\BddObject {

    use \Model\Name ;
    
    public static function getNameField() {
        return "name" ;
    }
    
    public function touch() {
        self::execRequest(""
                . " update `" . self::getTableName() . "`"
                . " set `last` = :last"
                . " where id = :id",
                array (
                    "last"  => time(),
                    "id"    => $this->getId()
                )) ;
    }
    
    public function getImage($type = null, $class = null) {
        $obj = new \Quantyl\XML\Html\Object("/Game/Avatar/Character?character={$this->id}&type=$type", "image/svg+xml") ;

        if ($class != null) {
            $obj->setAttribute("class", $class) ;
        } else if ($type != null) {
            $obj->setAttribute("class", "avatar-$type") ;
        } else {
            $obj->setAttribute("class", "avatar") ;
        }
        
        return $obj ;
    }
    
    public function getConnectionStatus() {
        $dt = time() - $this->last ;
        if ($dt < 5 * 60) {
            return 0 ;
        } else if ($dt < 20 * 60) {
            return 1 ;
        } else {
            return 2 ;
        }
    }
    
    public function getConnectionImg($class = null) {
        switch ($this->getConnectionStatus()) {
            case 0 :
                return new \Quantyl\XML\Html\Img("/Media/icones/character/online.png", \I18n::ONLINE(), $class) ;
            case 1 :
                return new \Quantyl\XML\Html\Img("/Media/icones/character/connected.png", \I18n::CONNECTED(), $class) ;
            case 2 :
                return new \Quantyl\XML\Html\Img("/Media/icones/character/offline.png", \I18n::OFFLINE(), $class) ;
        }
    }
    
    public function create() {
        $this->time        = 100000 ;
        $this->experience  =      0 ;
        $this->level       =      0 ;
        $this->point       =      5 ;
        $this->energy      =   1000 ;
        $this->hydration   =   1000 ;
        $this->inventory   =      5 ;
        $this->health      =    100 ;
        $this->credits     =   2500 ;
        $this->last_update = time() ;
        parent::create() ;
        
        $this->race->initInventory($this) ;
        
        foreach (Skill\Skill::getStart() as $sk) {
            Skill\Character::LearnFromCharacterAndSkill($this, $sk, 100) ;
        }
        
        \Model\Stats\Game\Moves::Visit($this, $this->position) ;
        
    }
    
    public function update($postlock = null) {
        $this->time        = $this->getTime() ;
        $this->energy      = $this->getEnergy() ;
        $this->hydration   = $this->getHydration() ;
        $this->last_update = time() ;
        if ($postlock !== null) {
            $this->locked = $postlock ;
        }
        parent::update() ;
    }
    
    public function lock() {
        $this->update(true) ;
    }
    
    public function unlock() {
        $this->update(false) ;
    }
    
    
    private function interpolate($value, $coef, $step, $min, $max) {
        
        if ($this->locked) {
            return $value ;
        }
        
        $dt = time() - $this->last_update ;
        
        return max($min,
                min($max,
                        $value + $coef * floor($dt / $step)
                        )) ;
    }
    
    public function getSpeed() {
        $base = 1.0 ;
        foreach (Character\Modifier::getActive($this) as $mod) {
            $base += $mod->modifier->speed ;
        }
        return $base ;
    }
    
    public function getTime() {
        
        $base = $this->interpolate($this->time, 1 , 1, 0, 100000) ;
        $now = time() ;
        foreach (Character\Modifier::getActive($this, $this->last_update) as $mod) {
            if ($mod->until > 0) {
                $base += $mod->modifier->speed * ($now - $mod->until);
            } else {
                $base += $mod->modifier->speed * ($now - $mod->las_update);
            }
        }
        return min($base, $this->getMaxTime()) ;
    }
    
    public function getMaxTime(){
        return 100000 * $this->getSpeed() ;
    }
    
    public function addTime($dt) {
        $this->time += round($dt, 0) ;
    }
    
    public function getHydration() {
        return $this->interpolate($this->hydration, -0.5, 60, 0, $this->getMaxHydration()) ;
    }
    
    public function getMaxHydration(){
        return 1000;
    }
    
    public function getEnergy() {
        return $this->interpolate($this->energy, -0.5, 60, 0, $this->getMaxEnergy()) ;
    }
    
    public function getMaxEnergy(){
        return 1000;
    }
    
    public function feed($energy, $hydration) {
        $this->update() ;
        $this->energy += $energy ;
        $this->hydration += $hydration ;
        $this->update() ;
    }
    
    public function eat($points) {
        $this->energy = $this->energy + $points ;
    }
    
    public function drink($points) {
        $this->hydration = $this->hydration + $points ;
    }
    
    public function getTimeModifier() {
        $e = max(1.0, 750.0 / max(1, $this->getEnergy()));
        $h = max(1.0, 750.0 / max(1, $this->getHydration())) ;
        
        return $e + $h - 1.0;
    }
    
    public function getCredits() {
        return $this->credits ;
    }
    
    public function addCredits($amount) {
        $this->credits += $amount ;
    }
    
    public function getExpThreshold($rel = 0) {
        $coef = 25000 ;
        return $coef * ($this->level+1+$rel) * ($this->level+2+$rel) / 2 ;
    }
    
    public function addExperience($amount) {
        $this->experience += $amount ;
        
        $threshold = $this->getExpThreshold() ;

        if ($this->experience >= $threshold) {
            $this->gainLevel(1) ;
        }
    }
   
    public function getExperience(){
        return $this->experience;
    }
    
    public function gainLevel($amount) {
        $this->level += $amount ;
        $this->point += $amount ;
        $this->health += 100 * $amount;
    }
    
    public function addExperienceBySkill($amount, \Model\Game\Skill\Character $cs) {
        $msg = "" ;
        $prev = $this->level ;
        $this->addExperience($amount) ;
        if ($prev != $this->level) {
            $msg .= \I18n::CHARACTER_LEVEL_UP($this->level, $this->point) ;
        }
        return $msg ;
    }
    
    public function getHonorary() {
        $metiers = array() ;
        foreach (Skill\Metier::getBest($this, 2) as $row) {
            $uses = $row["uses"] ;
            unset($row["uses"]) ;
            $m = Skill\Metier::createFromRow($row) ;
            
            $metiers[] = ""
                    . $m->getName() . " "
                    . $m->getMedalImg($this, "icone-inline", $uses)
                ;
        }
        return implode(", ", $metiers) ;
    }
    
    public function getHealth() {
        return round($this->health) ;
    }
    
    public function getMaxHealth() {
        return ($this->level + 1) * 100  ;
    }
    
    public function heal($points) {
        $this->health = min($this->getMaxHealth(), $this->health + $points) ;
    }
    
    public function refuel() {
        if ($this->point < 0) {
            throw new \Exception(\I18n::EXP_REFUELING_WITHOUT_POINT()) ;
        }
        $this->update() ;
        $this->time          = 100000 ;
        $this->energy        = 1000 ;
        $this->hydration     = 1000 ;
        $this->health        = $this->getMaxHealth() ;
        $this->point        -= $this->refueling ;
        $this->refueling    += 1 ;
        $this->update() ;
    }
    
    public function attack($dammages, $dt) {
        /*
        echo "--------------------------------------------\n" ;
        echo "Attacking " . $this->getName() . "\n" ;
        echo "Dammages : $dammages\n" ;
        echo "DT       : $dt\n" ;
        */
        // 1. Discretion
        $allowed_dammages = min($dammages, $this->level) ;
        $discretion      = max(-9, Characteristic\Character::getValue($this, Characteristic::GetByName("Discretion"))) ;
        $discretion_coef = 10 / ($discretion + 10) ;
        $base_dammages   = min($dammages, $discretion_coef * $allowed_dammages) ;
        /*
        echo "-- Discretion\n" ;
        echo "Value / Coef     : $discretion => $discretion_coef\n" ;
        echo "Allowed dammages : $allowed_dammages\n" ;
        echo "Base dammages    : $base_dammages\n" ;
        */
        // 2. Defense
        $defense = max(0, Characteristic\Character::getValue($this, Characteristic::GetByName("Defense"), $this)) ;
        $blocked = min($defense, $base_dammages) ;
        $passed  = $base_dammages - $blocked ;
        /*
        echo "-- Defense\n" ;
        echo "Value   : $defense\n" ;
        echo "Blocked : $blocked\n" ;
        echo "Passed  : $passed\n" ;
        */
        // 3. resistance
        $resistance       = max(-9, Characteristic\Character::getValue($this, Characteristic::GetByName("Resistance"))) ;
        $resistance_coef  = 10 / ($resistance + 10) ;
        $health_taken     = 100 * $passed * $resistance_coef * $dt ;
        
        $this->health = max(0, $this->health - $health_taken) ;
        /*
        echo "-- Resistance\n" ;
        echo "Value / Coef : $resistance => $resistance_coef\n" ;
        echo "Health Taken : $health_taken\n" ;
        echo "Health After : {$this->health}\n" ;
        */
        // 4. impact
        $impact = max(0, Characteristic\Character::getValue($this, Characteristic::GetByName("Impact"))) ;
        $fitness = $blocked * $impact * $dt ;
        
        $this->position->fitness -= $fitness ;
        
        // 5. Energy and Hydration ...
        $nat_dmg = 0 
                + ($this->getEnergy()    == 0 ? 50 : 0)
                + ($this->getHydration() == 0 ? 50 : 0)
                ;
        $this->health = max(0, $this->health - $nat_dmg * $dt) ;
        
        $this->update() ;
        /*
        echo "-- Impact\n" ;
        echo "Value           : $impact\n" ;
        echo "Fitness removed : $fitness\n" ;
        echo "Fitness After   : {$this->position->fitness}\n" ;
        */
        return $dammages - $base_dammages ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "sex" :
                return \Model\Enums\Sex::GetById($value) ;
            case "race" :
                return \Model\Enums\Race::GetById($value) ;
            case "user" :
                return \Model\Identity\User::GetById($value) ;
            case "position" :
            case "nationality" :
                return ($value == null ? null : City::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "sex" :
            case "race" :
            case "user" :
                return $value->getId() ;
            case "position" :
            case "nationality" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }

    // Get some set
    
    public static function GetFromUser(\Model\Identity\User $user) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `user` = :uid" ;
        return static::getResult($query, array("uid" => $user->getId())) ;
    }
    
    public function getFriends() {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  id in ("
                . "   select a as id"
                . "   from `" . \Model\Game\Social\Friend::getTableName() . "`"
                . "   where b = :id)",
                array("id" => $this->getId())
                ) ;
    }
    
    public function getFollower() {
        // They follow me
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  id in ("
                . "   select a as id"
                . "   from `" . \Model\Game\Social\Follower::getTableName() . "`"
                . "   where b = :id)",
                array("id" => $this->id)) ;
    }
    
    public function getFollowing() {
        // I follow them
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  id in ("
                . "   select b as id"
                . "   from `" . \Model\Game\Social\Follower::getTableName() . "`"
                . "   where a = :id)",
                array("id" => $this->id)) ;
    }
    
    public function isCitizen($c) {
        return $c !== null && Citizenship::IsCitizen($this, $c) ;
    }
    
    public static function GetFromCitizenship(City $c) {
        return Citizenship::GetCitizen($c) ;
    }
    
    public static function CountUnlockedCitizen(City $c) {
        $row = static::getSingleRow(
                "select count(*) as c"
                . " from"
                . "     `" . static::getTableName() . "` as ch,"
                . "     `" . Citizenship::getTableName() . "` as ci"
                . " where"
                . "     ci.city = :cid and"
                . "     ci.`character` = ch.id and"
                . "     not ch.locked",
                array(
                    "cid" => $c->getId()
                        )
                ) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function CountCitizen(City $city) {
        return Citizenship::CountCitizen($city) ;
    }
    
    public static function GetPopulation(City $c) {
        return static::getResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " position = :cid",
                array(
                    "cid" => $c->getId()
                        )
                ) ;
    }
    
    public static function CountPopulation(City $c) {
        $row = static::getSingleRow(
                "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " position = :cid",
                array(
                    "cid" => $c->getId()
                        )
                ) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    
    public static function GetBySearch($search) {
        $search = "%$search%" ;
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `name` like :s",
                array("s" => $search)
                ) ;
    }
    
    public static function GetNetwork(Character $me) {
        
        return static::getStatement(""
                . " select"
                . "     gc.id   as him,"
                . "     gsra.id as reqa,"
                . "     gsrb.id as reqb,"
                . "     gsf.id  as friend,"
                . "     gsfa.id as followed,"
                . "     gsfb.id as following"
                . " from"
                . "     game_character as gc"
                . " left join (select * from game_social_request  where a = :id) as gsra on gsra.b = gc.id"
                . " left join (select * from game_social_request  where b = :id) as gsrb on gsrb.a = gc.id"
                . " left join (select * from game_social_friend   where a = :id) as gsf  on gsf.b  = gc.id"
                . " left join (select * from game_social_follower where a = :id) as gsfa on gsfa.b = gc.id"
                . " left join (select * from game_social_follower where b = :id) as gsfb on gsfb.a = gc.id"
                . " where"
                . "     not isnull(gsra.id) or"
                . "     not isnull(gsrb.id) or"
                . "     not isnull(gsf.id) or"
                . "     not isnull(gsfa.id) or"
                . "     not isnull(gsfb.id)",
                array("id" => $me->id)) ;
        
        
    }
    
    public static function getForUpdate(City $city) {
        return static::getResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " position = :cid"
                . " order by health desc",
                array(
                    "cid" => $city->getId()
                        )
                ) ;
    }
    
}