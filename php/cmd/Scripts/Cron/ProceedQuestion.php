<?php

namespace Scripts\Cron ;

class ProceedQuestion extends \Scripts\Base {
    
    public function doStuff() {
        foreach (\Model\Game\Politic\Question::toProceed() as $question) {
            $this->proceed($question) ;
        }
    }
    
    public function proceed(\Model\Game\Politic\Question $q) {
        echo "--[ " . $q->system->city->getName() . " - " . $q->type->getName() . " - " . \I18n::_date_time($q->end) . "\n" ;
        
        if ($q->system->end != null && $q->system->end < time()) {
            // system has changed
            $q->processed = true ;
            $q->update() ;
        } else {
            $methodname = "proceed" . $q->type->getValue() ;
            $res = $this->$methodname($q) ;
            $q->processed = $res ;
            $q->update() ;
        }
    }
    
    public function proceedGovernment(\Model\Game\Politic\Question $q) {
        list ($voters, $votes, $yes) = \Model\Game\Politic\Vote::getResult($q) ;
        $q->turnout = $votes / $voters ;
        $q->answer = $yes / $votes ;
        $q->update() ;
        
        $now = time() ;
        $truesyst = $q->system->getPoliticalSystem() ;
        
        if ($q->turnout >= $truesyst->gov_quorum && $q->answer > $truesyst->gov_threshold) {
            $last_gov = \Model\Game\Politic\Government::CurrentFromSystem($q->system) ;
            if ($last_gov != null) {
                $last_gov->end = $now ;
                $last_gov->update() ;
            }
            $new_gov = \Model\Game\Politic\Government::GetFromQuestion($q) ;
            $new_gov->start = $now ;
            $new_gov->update() ;
            
        } else {
            $new_gov = \Model\Game\Politic\Government::GetFromQuestion($q) ;
            $new_gov->end = $now ;
            $new_gov->start = $now ;
            $new_gov->update() ;
        }
        
        return true ;
    }
    
    public function proceedSystem(\Model\Game\Politic\Question $q) {
        list ($voters, $votes, $yes) = \Model\Game\Politic\Vote::getResult($q) ;
        $q->turnout = $votes / $voters ;
        $q->answer = $yes / $voters ;
        $q->update() ;
        
        $now = time() ;
        $truesyst = $q->system->getPoliticalSystem() ;
        
        if ($q->turnout >= $truesyst->sys_quorum && $q->answer > $truesyst->sys_threshold) {
            
            $last_sys = $q->system ;
            $last_sys->end = $now ;
            $last_sys->update() ;
            
            $new_sys = \Model\Game\Politic\System::GetFromQuestion($q) ;
            $new_sys->start = $now ;
            $new_sys->end   = null ;
            $new_sys->update() ;
            $new_sys->doStart() ;
            
        } else {
            $new_sys = \Model\Game\Politic\System::GetFromQuestion($q) ;
            $new_sys->start = $now ;
            $new_sys->end = $now ;
            $new_sys->update() ;
        }
        
        return true ;
    }
    
    public function gotPresident(\Model\Game\Politic\Question $q, \Model\Game\Politic\Republic $republic) {

        $last_pres = \Model\Game\Politic\President::GetLastFromRepublic($republic) ;
        $last_pres->end = time() ;
        $last_pres->update() ;

        $new_pres = \Model\Game\Politic\President::createFromValues(array(
            "republic" => $republic,
            "character" => \Model\Game\Politic\Candidate::getChosenOne($q)->character,
            "question" => $q,
            "start" => time()
        )) ;
        
    }
    
    public function nextPresident(\Model\Game\Politic\Question $q, \Model\Game\Politic\Republic $republic, $duration) {
        $end   = time() + $duration             * 24 * 3600 ;
        $start = $end   - $republic->pres_delay * 24 * 3600 ;
        
        \Model\Game\Politic\Question::createFromValues(array(
            "system" => $republic->system,
            "type"   => \Model\Game\Politic\QuestionType::President(),
            "vote"   => $republic->pres_type,
            "start"  => $start,
            "end"    => $end,
            "processed" => false,
            "point"  => $republic->pres_point
        )) ;
    }
    
    public function proceedPresident(\Model\Game\Politic\Question $q) {
        $republic = $q->system->getPoliticalSystem() ;
        
        // sort candidates
        $this->sortCandidates($q) ;
        
        // Get ranked <= 1
        $cnt = \Model\Game\Politic\Candidate::CountRanked($q, 1) ;
        if ($cnt == 1) {
            \Model\Game\Politic\Candidate::ChoseRanked($q, 1) ;
            $this->gotPresident($q, $republic) ;
            $this->nextPresident($q, $republic, $republic->duration) ;
        } else {
            \Model\Game\Politic\Candidate::ChoseRanked($q, 0) ;
            $this->nextPresident($q, $republic, 2 * $republic->pres_delay) ;
        }
        
        return true ;
    }
    
    public function proceedParliament(\Model\Game\Politic\Question $q) {
        $parliamentary = $q->system->getPoliticalSystem() ;
        $this->sortCandidates($q) ;
        
        // Get ranked <= $seats
        $cnt = \Model\Game\Politic\Candidate::CountRanked($q, $parliamentary->seats) ;
        if ($cnt == 1) {
            \Model\Game\Politic\Candidate::ChoseRanked($q, $parliamentary->seats) ;
            $this->gotParliament($q, $parliamentary) ;
            $this->nextParliament($q, $parliamentary, $parliamentary->duration) ;
        } else {
            \Model\Game\Politic\Candidate::ChoseRanked($q, 0) ;
            $this->nextParliament($q, $parliamentary, 2 * $parliamentary->parl_delay) ;
        }
        
        return true ;
    }
    
    public function gotParliament(\Model\Game\Politic\Question $q, \Model\Game\Politic\Parliamentary $parliamentary) {
        $now = time() ;
        
        $last = \Model\Game\Politic\Parliament::getLastFromParliamentary($parliamentary) ;
        $last->end = $now ;
        $last->update() ;
        
        $new = \Model\Game\Politic\Parliament::getFromQuestion($q) ;
        $new->start = $now() ;
        $new->update() ;
        
        foreach (\Model\Game\Politic\Candidate::getChosen($q) as $parl) {
            \Model\Game\Politic\Parliamentarian::createFromValues(array(
                "parliament" => $new,
                "character" => $parl->character
            )) ;
        }
    }
    
    public function nextParliament(\Model\Game\Politic\Question $q, \Model\Game\Politic\Parliamentary $parliamentary, $duration) {
        $end   = time() + $duration                  * 24 * 3600 ;
        $start = $end   - $parliamentary->parl_delay * 24 * 3600 ;
        
        \Model\Game\Politic\Question::createFromValues(array(
            "system" => $parliamentary->system,
            "type"   => \Model\Game\Politic\QuestionType::Parliament(),
            "vote"   => $parliamentary->parl_type,
            "start"  => $start,
            "end"    => $end,
            "processed" => false,
            "point"  => $parliamentary->parl_point
        )) ;
    }

    public function sortCandidates(\Model\Game\Politic\Question $q) {
        
        $fnct = "sort" . $q->vote->getValue() ;
        return \Model\Game\Politic\Candidate::$fnct($q) ;
        
    }
    
}
