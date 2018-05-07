<?php

namespace Model\Game\Politic ;

trait BasicPoliticalSystem {
    
    public static function GetFromSystem(System $s) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `system` = :sid",
                array("sid" => $s->id)) ;
    }
    
    public function createGovernmentProject(\Model\Game\Character $author) {
        if ($this->canManage($author)) {
            return Government::createFromValues(array(
                "system" => $this->system,
                "author" => $author
            )) ;
        } else {
            throw new \Exception() ;
        }
    }

    public function proceedGovernmentProjectDirect(Government $gov) {
        if ($gov->system->equals($this->system) && $this->canManage($gov->author)) {
            $last = Government::CurrentFromSystem($this->system) ;
            if ($last != null) {
                $last->end = time() ;
                $last->update() ;
            }
            
            $gov->start = time() ;
            $gov->update() ;
        } else {
            throw new \Exception() ;
        }
    }
    
    public function proceedGovernmentProjectWithQuestion(Government $gov) {
        if ($gov->system->equals($this->system) && $this->canManage($gov->author)) {
            
            $now = time() ;
            $question = Question::createFromValues(array(
                "system" => $gov->system,
                "type"   => QuestionType::Government(),
                "vote"   => VoteType::System(),
                "start"  => $now,
                "end"    => $now + $this->gov_delay * 24 * 3600,
                "point"  => 1
            )) ;
            
            $gov->question = $question ;
            $gov->update() ;
        } else {
            throw new \Exception() ;
        }
    }
}