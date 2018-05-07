<?php

namespace Model\Game\Politic\VoteSystem ;

use Model\Game\Politic\Candidate;
use Model\Game\Politic\Choice;
use Model\Game\Politic\Question;

class Alternative {
    
    private $_question ;
    
    public function __construct(Question $q) {
        $this->_question = $q ;
    }
    
    public function sort() {
        
        // Init preferences
        $preferences = $this->initPreferences() ;
        $candidates = $this->initCandidates() ;
        
        // Iteratively delete least prefered candidate
        $outed = $this->iterativeDelete($preferences, $candidates) ;
        
        // Make rank for each outed, reverse order
        $this->makeRank($outed, $candidates) ;
        
    }
    
    public function initCandidates() {
        $cids = array() ;
        foreach (Candidate::GetFromQuestion($this->_question) as $cand) {
            $cids[$cand->id] = $cand ;
        }
        return $cids ;
    }
    
    public function initPreferences() {
        $preferences = array() ;
        foreach (Choice::GetForQuestion($this->_question) as $choice) {
            $cid = $choice->character->id ;
            if (! isset($preferences[$cid])) {
                $preferences[$cid] = array() ;
            }
            $preferences[$cid][$choice->value] = $choice->candidate->id ;
        }
        
        $res = array() ;
        foreach ($preferences as $cid => $list) {
            ksort($list) ;
            $res[$cid] = $list ;
        }
        
        return $res ;
    }
    
    public function iterativeDelete($preferences, $candidates) {
        $outed = array() ;
        while (count($preferences) > 0) {
            $leasts = $this->getLeast($preferences, $candidates) ;
            $outed[] = $leasts ;
            foreach ($leasts as $cid) {
                $preferences = $this->delCandidate($preferences, $cid) ;
                unset($candidates[$cid]) ;
            }
        }
        return $outed ;
    }
    
    public function getLeast($preferences, $candidates) {
        
        $scores = array() ;
        foreach ($candidates as $cid => $dummy) {
            $scores[$cid] = 0 ;
        }
        
        foreach ($preferences as $list) {
            $first = reset($list) ;
            $scores[$first] += 1 ;
        }
        
        $ranks = array() ;
        foreach ($scores as $cid => $value) {
            if (! isset($ranks[$value])) {
                $ranks[$value] = array() ;
            }
            $ranks[$value][] = $cid ;
        }
        
        krsort($ranks) ;
        return end($ranks) ;
    }
    
    public function delCandidate($preferences, $cand) {
        
        $res = array() ;
        foreach ($preferences as $cid => $list) {
            $key = array_search($cand, $list) ;
            if ($key !== false) {
                unset($list[$key]) ;
            }
            if (count($list) > 0) {
                $res[$cid] = $list ;
            }
        }
        return $res ;
    }
    
    public function makeRank($outed, $candidates) {
        $best = end($outed) ;
        $rank = 1 ;
        while ($best !== false) {
            foreach ($best as $cid) {
                $tc = $candidates[$cid] ;
                $tc->rank = $rank ;
                $tc->update() ;
            }
            $rank += count($best) ;
            
            $best = prev($outed) ;
        }
    }
    
}
