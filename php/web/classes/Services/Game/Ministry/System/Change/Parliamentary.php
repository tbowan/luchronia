<?php


namespace Services\Game\Ministry\System\Change ;

class Parliamentary extends Base {
    
    public function createTargetSystem($name, $specific) {
        
        $system = \Model\Game\Politic\System::createFromValues(array(
            "city" => $this->city,
            "type" => \Model\Game\Politic\SystemType::Parliamentary(),
            "name" => $name
        )) ;
        
        $parliamentary = \Model\Game\Politic\Parliamentary::createFromValues(array(
            "system"        => $system,
            
            "duration"      => $specific["parliament"]["duration"],
            "seats"         => $specific["parliament"]["seats"],
            "parl_delay"     => $specific["parliament"]["delay"],
            "parl_type"      => $specific["parliament"]["type"],
            "parl_point"     => max(1, $specific["parliament"]["point"]),
            
            "gov_delay"     => $specific["gov"]["delay"],
            "gov_quorum"    => $specific["gov"]["quorum"],
            "gov_threshold" => $specific["gov"]["threshold"],
            
            "sys_delay"     => $specific["sys"]["delay"],
            "sys_quorum"    => $specific["sys"]["quorum"],
            "sys_threshold" => $specific["sys"]["threshold"],
        )) ;
        
        // first parliament
        $parliament = \Model\Game\Politic\Parliament::createFromValues(array(
            "parliamentary" => $parliamentary,
            "question"      => null,
            "start"         => time(),
            "end"           => null
        )) ;
        
        // Les parlementaires
        foreach ($specific["parliamentarian"] as $parl) {
            \Model\Game\Politic\Parliamentarian::createFromValues(array(
                "parliament" => $parliament,
                "character" => $parl
            )) ;
        }
        
        return $system ;
    }


    public function getSpecificFieldSet() {
        
        $params = new \Quantyl\Form\FieldSet(\I18n::PARLIAMENTARY_PARAMETERS()) ;
        
        // Initial senators
        $params->addInput("parliamentarian", new \Form\Character\Citizen($this->city, \I18n::INITIAL_PARLIAMENTARIANS())) ;
        
        // Admissions
        $par = $params->addInput("parliament", new \Quantyl\Form\FieldSet(\I18n::PARLIAMENT())) ;
        $par->addInput("duration",  new \Quantyl\Form\Fields\Integer(\I18n::PARLIAMENT_DURATION(), true)) ;
        $par->addInput("seats",     new \Quantyl\Form\Fields\Integer(\I18n::PARLIAMENT_SEATS(), true)) ;
        $par->addInput("delay",     new \Quantyl\Form\Fields\Integer(\I18n::DELAY(), true)) ;
        $par->addInput("type",      new \Form\VoteType(\I18n::VOTE_TYPE(), true)) ;
        $par->addInput("point",     new \Quantyl\Form\Fields\Integer(\I18n::VOTE_POINT(), true)) ;
        
        // Government change
        $gov = $params->addInput("gov", new \Quantyl\Form\FieldSet(\I18n::GOVERNMENT_CHANGE())) ;
        $gov->addInput("delay",     new \Quantyl\Form\Fields\Integer(\I18n::DELAY(), true)) ;
        $gov->addInput("quorum",    new \Quantyl\Form\Fields\Percentage(\I18n::QUORUM(), true)) ;
        $gov->addInput("threshold", new \Quantyl\Form\Fields\Percentage(\I18n::THRESHOLD(), true)) ;
        
        // System change
        $sys = $params->addInput("sys", new \Quantyl\Form\FieldSet(\I18n::SYSTEM_CHANGE())) ;
        $sys->addInput("delay",     new \Quantyl\Form\Fields\Integer(\I18n::DELAY(), true)) ;
        $sys->addInput("quorum",    new \Quantyl\Form\Fields\Percentage(\I18n::QUORUM(), true)) ;
        $sys->addInput("threshold", new \Quantyl\Form\Fields\Percentage(\I18n::THRESHOLD(), true)) ;
        
        
        return $params ;
    }

}
