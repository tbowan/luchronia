<?php

namespace Model\Game\Politic ;

class Republic extends \Quantyl\Dao\BddObject implements PoliticalSystem {
    
    use BasicPoliticalSystem ;
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            case "pres_type" :
                return VoteType::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
            case "pres_type" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

    public function canManage(\Model\Game\Character $c) {
        $president = President::GetLastFromRepublic($this) ;
        return $president != null && $c->equals($president->character) ;
    }

    public function countVoter() {
        return \Model\Game\Character::CountCitizen($this->system->city) ;
    }

    public function doChange(System $target) {
        $current = $this->system ;
        $current->end = time() ;
        $current->update() ;
        
        $president = President::GetLastFromRepublic($this) ;
        if ($president != null) {
            $president->end = time() ;
            $president->update() ;
        }
        
        $target->start = time() ;
        $target->update() ;
    }

    public function doStart() {
        // Organisation des premières élections présidentielles
        $now   = time() ;
        $end   = $now + $this->duration * 24 * 3600 ;
        $start = $end - $this->pres_delay * 24 * 3600 ;
        
        $question = Question::createFromValues(array(
            "system" => $this->system,
            "type"   => QuestionType::President(),
            "vote"   => $this->pres_type,
            "start"  => $start,
            "end"    => $end,
            "processed" => false,
            "point"  => $this->pres_point
        )) ;
        
        return ;
    }
    
    public function proceedGovernmentProject(Government $gov) {
        return $this->proceedGovernmentProjectDirect($gov) ;
    }

    public function tryChange(System $target) {
        $question = Question::createFromValues(array(
            "system" => $this->system,
            "type" => QuestionType::System(),
            "vote" => VoteType::System(),
            "start" => time(),
            "end" => time() + $this->sys_delay * 24*3600,
            "processed" => false
        )) ;
        
        $target->question = $question ;
        $target->update() ;
    }

}
