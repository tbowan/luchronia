<?php


namespace Services\Game\Ministry\System\Change ;

class Senate extends Base {
    
    public function createTargetSystem($name, $specific) {
        
        $system = \Model\Game\Politic\System::createFromValues(array(
            "city" => $this->city,
            "type" => \Model\Game\Politic\SystemType::Senate(),
            "name" => $name
        )) ;
        
        $senate = \Model\Game\Politic\Senate::createFromValues(array(
            "system"        => $system,
            "admission"     => $specific["access"]["admission"],
            "revocation"    => $specific["access"]["revocation"],
            "gov_delay"     => $specific["gov"]["delay"],
            "gov_quorum"    => $specific["gov"]["quorum"],
            "gov_threshold" => $specific["gov"]["threshold"],
            "sys_delay"     => $specific["sys"]["delay"],
            "sys_quorum"    => $specific["sys"]["quorum"],
            "sys_threshold" => $specific["sys"]["threshold"],
        )) ;
        
        $now = time() ;
        foreach ($specific["senators"] as $senator) {
            \Model\Game\Politic\Senator::createFromValues(array(
                "senate" => $senate,
                "character" => $senator,
                "start" => $now,
                "end" => null,
            )) ;
        }
        
        return $system ;
    }


    public function getSpecificFieldSet() {
        
        $params = new \Quantyl\Form\FieldSet(\I18n::SENATE_PARAMETERS()) ;
        
        // Initial senators
        $params->addInput("senators", new \Form\Character\Citizen($this->city, \I18n::INITIAL_SENATORS())) ;
        
        // Admissions
        $th = $params->addInput("access", new \Quantyl\Form\FieldSet(\I18n::SENATE_ACCESS())) ;
        $th->addInput("admission",  new \Quantyl\Form\Fields\Percentage(\I18n::SENATE_ADMISSION(), true)) ;
        $th->addInput("revocation", new \Quantyl\Form\Fields\Percentage(\I18n::SENATE_REVOCATION(), true)) ;
        
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
