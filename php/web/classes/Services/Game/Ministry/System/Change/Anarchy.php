<?php


namespace Services\Game\Ministry\System\Change ;

class Anarchy extends Base {
    
    public function createTargetSystem($name, $specific) {
        $system = \Model\Game\Politic\System::createFromValues(array(
            "city" => $this->city,
            "type" => \Model\Game\Politic\SystemType::Anarchy(),
            "name" => $name
        )) ;
        
        return $system ;
    }

    public function getSpecificFieldSet() {
        return ;
    }

}
