<?php

namespace Model\Game\Politic ;

class Parliamentary extends \Quantyl\Dao\BddObject implements PoliticalSystem {
    
    use BasicPoliticalSystem ;
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            case "parl_type" :
                return VoteType::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
            case "parl_type" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

    public function canManage(\Model\Game\Character $c) {
        $parliament = Parliament::getLastFromParliamentary($this) ;
        return ($parliament != null && Parliamentarian::canManage($parliament, $c)) ;
    }

    public function countVoter() {
        $parliament = Parliament::getLastFromParliamentary($this) ;
        return ($parliament == null ? 0 : Parliamentarian::CountFromParliament($parliament) );
    }

    public function doChange(System $target) {
        $current = $this->system ;
        $current->end = time() ;
        $current->update() ;
        
        // End each parliamentarian
        $parliament = Parliament::getLastFromParliamentary($this) ;
        if ($parliament != null) {
            $parliament->end = time() ;
            $parliament->update() ;
        }
        
        
        $target->start = time() ;
        $target->update() ;
    }

    public function doStart() {
        // Organisation des premières élections parlementaires
        $now   = time() ;
        $end   = $now + $this->duration * 24 * 3600 ;
        $start = $end - $this->par_delay * 24 * 3600 ;
        
        $question = Question::createFromValues(array(
            "system" => $this->system,
            "type"   => QuestionType::Parliament(),
            "vote"   => $this->parl_type,
            "start"  => $start,
            "end"    => $end,
            "processed" => false,
            "point"  => $this->parl_point
        )) ;
        
        return ;
    }

    public function proceedGovernmentProject(Government $gov) {
        $this->proceedGovernmentProjectWithQuestion($gov) ;
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
