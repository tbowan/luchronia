<?php

namespace Model\Game\Politic\VoteSystem ;

use Model\Game\Politic\Candidate;
use Model\Game\Politic\Choice;
use Model\Game\Politic\Question;
use Model\Game\Politic\QuestionType;

class Hare {
    
    private $_question ;
    
    public function __construct(Question $q) {
        $this->_question = $q ;
    }
    
    public function sort() {
        
        
        // Init phase
        $preferences = array() ;
        $electors    = array() ;
        
        $total = $this->initPreferences($preferences, $electors) ;
        $candidates = $this->initCandidates() ;
        
        $places = $this->initPlaces() ;
        $threshold = $total / ($places + 1) ;
        
        $scores = $this->getScores($candidates, $preferences, $electors) ;
        
        $elected = array() ;
        $outed   = array() ;
        
        
        while (count($candidates) > 0) {
            $new = $this->getElected($scores, $threshold) ;
            if (count($new) > 0) {
                $elected[] = $new ;
                foreach ($new as $cid) {
                    $transfer = ($scores[$cid] - $threshold) / $scores[$cid] ;
                    $total = $this->delCandidate($cid, $preferences, $electors, $candidates, $transfer) ;
                }
                $places -= count($new) ;
            } else {
                $out = $this->getLeast($scores, $threshold) ;
                $outed[] = $out ;
                foreach ($out as $cid) {
                    $total = $this->delCandidate($cid, $preferences, $electors, $candidates, 0) ;
                }
            }
            $scores = $this->getScores($candidates, $preferences, $electors) ;
            
            $threshold = $total / ($places + 1) ;
        }
        
        // Got it
        $rank = 1 ;
        foreach ($elected as $list) {
            foreach ($list as $cid) {
                $tc = Candidate::GetById($cid) ;
                $tc->rank = $rank ;
                $tc->update() ;
            }
            $rank += count($list) ;
        }
        
        $least = end($outed) ;
        while ($least !== false) {
            foreach ($least as $cid) {
                $tc = Candidate::GetById($cid) ;
                $tc->rank = $rank ;
                $tc->update() ;
            }
            $rank += count($least) ;
            $least = prev($outed) ;
        }
    }
    
    public function initCandidates() {
        $cids = array() ;
        foreach (Candidate::GetFromQuestion($this->_question) as $cand) {
            $cids[$cand->id] = $cand ;
        }
        return $cids ;
    }
    
    public function initPreferences(&$preferences, &$electors) {
        $temp = array() ;
        foreach (Choice::GetForQuestion($this->_question) as $choice) {
            $cid = $choice->character->id ;
            if (! isset($temp[$cid])) {
                $temp[$cid] = array() ;
            }
            $temp[$cid][$choice->value] = $choice->candidate->id ;
        }
        
        foreach ($temp as $cid => $list) {
            ksort($list) ;
            $preferences[$cid] = $list ;
            $electors[$cid] = 1.0 ;
        }
        
        return count($electors) ;
    }
    
    public function initPlaces() {
        if ($this->_question->type->equals(QuestionType::Parliament())) {
            $ts = $this->_question->system->getPoliticalSystem();
            return $ts->seats ;
        } else {
            return 1.0 ;
        }
    }
    
    public function getScores($candidates, $preferences, $electors) {
        $scores = array() ;
        foreach ($candidates as $cid => $dummy) {
            $scores[$cid] = 0 ;
        }
        
        foreach ($preferences as $eid => $list) {
            $best = reset($list) ;
            $scores[$best] += $electors[$eid] ;
        }
        return $scores ;
    }
    
    public function getElected($scores, $threshold) {
        $elected = array() ;
        foreach ($scores as $cid => $value) {
            if ($value > $threshold) {
                $elected[] = $cid ;
            }
        }
        return $elected ;
    }
    
    public function getLeast($scores, $threshold) {
        $least = array() ;
        $min = $threshold ;
        
        foreach ($scores as $cid => $value) {
            if ($value < $min) {
                $least = array($cid) ;
                $min = $value ;
            } else if ($value == $min) {
                $least[] = $cid ;
            }
        }
        return $least ;
    }
    
    public function delCandidate($removed, &$preferences, &$electors, &$candidates, $transfer) {
        $new_pref = array() ;
        foreach ($preferences as $eid => $list) {
            // Transfert de voix
            if (reset($list) == $removed) {
                $electors[$eid] += $transfer ;
            }
            
            // Suppression du candidat
            $key = array_search($removed, $list) ;
            if ($key !== false) {
                unset($list[$key]) ;
            }
            
            // reste des candidats ?
            if (count($list) == 0) {
                unset($electors[$eid]) ;
            } else {
                $new_pref[$eid] = $list ;
            }
        }
        
        unset($candidates[$removed]) ;
        
        $preferences = $new_pref ;
        
        $total = 0 ;
        foreach ($electors as $eid => $value) {
            $total += $value ;
        }
        return $total ;
    }
    
}
