<?php

namespace Model\Game\Politic ;

class Monarchy extends \Quantyl\Dao\BddObject implements PoliticalSystem {
    
    use BasicPoliticalSystem ;
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            case "king" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
            case "king" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

    public function countVoter() {
        return 1 ;
    }
    
    public function canManage(\Model\Game\Character $c) {
        return $this->king->equals($c) ;
    }

    public function doChange(System $target) {
        $current = $this->system ;
        $current->end = time() ;
        $current->update() ;
        
        $target->start = time() ;
        $target->update() ;
    }

    public function doStart() {
        return ;
    }
    
    public function tryChange(System $target) {
        $this->system->doChange($target) ;
        return $target ;
    }

    public function proceedGovernmentProject(Government $gov) {
        return $this->proceedGovernmentProjectDirect($gov) ;
    }

}
