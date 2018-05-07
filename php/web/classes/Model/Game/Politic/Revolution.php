<?php

namespace Model\Game\Politic ;

class Revolution extends \Quantyl\Dao\BddObject implements PoliticalSystem {
    
    use BasicPoliticalSystem ;
    use \Model\Name ;
    
    
    public function isSecret() {
        return $this->system->start === null ;
    }
    
    public function isDeclared() {
        return ! $this->isSecret() && ! $this->isEnded() ;
    }
    
    public function isEnded() {
        return $this->system->end != null && $this->system->end  < time() ;
    }
    
    public function create() {
        $this->secretid = static::generateSecretId() ;
        parent::create();
    }
    
    public function delete() {
        $this->proposed->delete() ;
    }
    
    public function getStatus() {
        return RevolutionStatus::getFromSystem($this->system) ;
    }
    
    public function checkThreshold() {
        
        $city    = $this->system->city ;
        $citizen = \Model\Game\Character::CountUnlockedCitizen($city) ;
        $favor   = Support::CountFromRevolution($this) ;
        
        if ($this->system->start == null && $favor * 3 > $citizen) {
            // Declare revolution (more than 1/3 citizen support the revolution
            $this->system->start = time() ;
            $this->system->end   = time() + 2 * 24 * 60 * 60 ; // 2 days
            $this->system->update() ;
        }
        
        if ($favor * 3 > $citizen * 2) {
            // The revolution pass
            $this->system->end = time() ;
            $this->system->update() ;

            $previous = System::LastFromCity($city, $this->system->start) ;
            $previous->doChange($this->proposed) ;
        }
        
        // TODO : if ($favor == 0) { $this->delete() ; }
        
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            case "proposed" :
                return System::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
            case "proposed" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `character` = :cid",
                array("cid" => $c->id)) ;
    }
    
    public static function GetFromSecretId($id) {
        static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `secretid` = :sid",
                array("sid" => $id)) ;
    }

    public static function generateSecretId() {
        $found = true ;
        while ($found) {
            $id = bin2hex(openssl_random_pseudo_bytes(8)) ;
            $rev = static::GetFromSecretId($id) ;
            $found = ($rev !== null) ;
        }
        return $id ;
    }

    public function countVoter() {
        return 0;
    }
    
    public function canManage(\Model\Game\Character $c) {
        return false ;
    }

    public function doChange(System $target) {
        $this->system->end = time() ;
        $this->system->update() ;
        
        $target->start = time() ;
        $target->update() ;
    }

    public function doStart() {
        return ;
    }
    
    public function tryChange(System $target) {
        throw new \Exception() ;
    }

    public function createGovernmentProject(\Model\Game\Character $author) {
        throw new \Exception() ;
    }

    public function proceedGovernmentProject(Government $gov) {
        throw new \Exception() ;
    }

    public static function getNameField() {
        return "secretid" ;
    }

}
