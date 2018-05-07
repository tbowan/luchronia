<?php


namespace Services\Game\Ministry\System\Change ;

class Monarchy extends Base {
    
    public function createTargetSystem($name, $specific) {
        $system = \Model\Game\Politic\System::createFromValues(array(
            "city" => $this->city,
            "type" => \Model\Game\Politic\SystemType::Monarchy(),
            "name" => $name
        )) ;
        
        $monarchy = \Model\Game\Politic\Monarchy::createFromValues(array(
            "system" => $system,
            "king"   => $specific["king"]
        )) ;
        
        return $system ;
    }


    public function getSpecificFieldSet() {
        $fs = new \Quantyl\Form\FieldSet(\I18n::MONARCHY()) ;
        $fs->addInput("king", new \Form\Character(\I18n::MONARCHY_KING())) ;
        return $fs ;
    }

}
