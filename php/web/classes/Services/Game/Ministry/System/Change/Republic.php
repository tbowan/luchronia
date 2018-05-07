<?php


namespace Services\Game\Ministry\System\Change ;

class Republic extends Base {
    
    public function createTargetSystem($name, $specific) {
        
        $system = \Model\Game\Politic\System::createFromValues(array(
            "city" => $this->city,
            "type" => \Model\Game\Politic\SystemType::Republic(),
            "name" => $name
        )) ;
        
        $republic = \Model\Game\Politic\Republic::createFromValues(array(
            "system"        => $system,
            "duration"      => $specific["pres"]["duration"],
            "pres_delay"    => $specific["pres"]["delay"],
            "pres_type"     => $specific["pres"]["type"],
            "pres_point"    => max(1, $specific["pres"]["point"]),
            
            "sys_delay"     => $specific["sys"]["delay"],
            "sys_quorum"    => $specific["sys"]["quorum"],
            "sys_threshold" => $specific["sys"]["threshold"],
        )) ;
        
        $president = \Model\Game\Politic\President::createFromValues(array(
            "republic" => $republic,
            "character" => $specific["president"],
            "question" => null,
            "start" => null,
            "end" => null
        )) ;
        
        return $system ;
    }


    public function getSpecificFieldSet() {
        
        $params = new \Quantyl\Form\FieldSet(\I18n::REPUBLIC_PARAMETERS()) ;
        
        // Initial senators
        $params->addInput("president", new \Form\Character\President($this->city, \I18n::INITIAL_PRESIDENT())) ;
        
        // Government change
        $pres = $params->addInput("pres", new \Quantyl\Form\FieldSet(\I18n::PRESIDENT())) ;
        $pres->addInput("duration",       new \Quantyl\Form\Fields\Integer(\I18n::MANDAT_DURATION(), true)) ;
        $pres->addInput("delay",          new \Quantyl\Form\Fields\Integer(\I18n::DELAY(), true)) ;
        $pres->addInput("type",           new \Form\VoteType(\I18n::VOTE_TYPE(), true)) ;
        $pres->addInput("point",          new \Quantyl\Form\Fields\Integer(\I18n::VOTE_POINT(), true)) ;
        
        // System change
        $sys = $params->addInput("sys", new \Quantyl\Form\FieldSet(\I18n::SYSTEM_CHANGE())) ;
        $sys->addInput("delay",     new \Quantyl\Form\Fields\Integer(\I18n::DELAY(), true)) ;
        $sys->addInput("quorum",    new \Quantyl\Form\Fields\Percentage(\I18n::QUORUM(), true)) ;
        $sys->addInput("threshold", new \Quantyl\Form\Fields\Percentage(\I18n::THRESHOLD(), true)) ;
        
        
        return $params ;
    }

}
