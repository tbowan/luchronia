<?php

namespace Model\Game\Politic ;

class Democracy extends \Quantyl\Dao\BddObject implements PoliticalSystem  {
    
    use BasicPoliticalSystem ;
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

    public function canManage(\Model\Game\Character $c) {
        return $c->isCitizen($this->system->city) ;
    }

    public function countVoter() {
        return \Model\Game\Character::CountCitizen($this->system->city) ;
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

    public function proceedGovernmentProject(Government $gov) {
        return $this->proceedGovernmentProjectWithQuestion($gov) ;
    }

    public function tryChange(System $target) {
        $question = Question::createFromValues(array(
            "system" => $this->system,
            "type" => QuestionType::System(),
            "vote" => VoteType::System(),
            "start" => time(),
            "end" => time() + $this->sys_delay * 24*3600,
            "processed" => false,
            "point" => 1
        )) ;
        
        $target->question = $question ;
        $target->update() ;
    }

}
