<?php


namespace Services\Game\Ministry\System\Change ;

class Democracy extends Base {
    
    public function createTargetSystem($name, $specific) {
        
        $system = \Model\Game\Politic\System::createFromValues(array(
            "city" => $this->city,
            "type" => \Model\Game\Politic\SystemType::Democracy(),
            "name" => $name
        )) ;
        
        $democracy = \Model\Game\Politic\Democracy::createFromValues(array(
            "system"        => $system,
            "gov_delay"     => $specific["gov"]["delay"],
            "gov_quorum"    => $specific["gov"]["quorum"],
            "gov_threshold" => $specific["gov"]["threshold"],
            "sys_delay"     => $specific["sys"]["delay"],
            "sys_quorum"    => $specific["sys"]["quorum"],
            "sys_threshold" => $specific["sys"]["threshold"],
        )) ;
        
        return $system ;
    }


    public function getSpecificFieldSet() {
        
        $params = new \Quantyl\Form\FieldSet(\I18n::DEMOCRACY_PARAMETERS()) ;
        
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
