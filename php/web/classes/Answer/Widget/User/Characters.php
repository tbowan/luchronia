<?php

namespace Answer\Widget\User ;

class Characters extends \Answer\Widget\Misc\Section {
    
    
    public function __construct(\Model\Identity\User $u, \Model\Game\Character $current, $classes = "") {
        parent::__construct();
        $this->_user = $u ;
        $this->_current = $current ;
        
        parent::__construct(\I18n::USER_CHARACTER(), "", "", $this->getCharacterTable($u, $current), $classes) ;
    }
    
    public function getCharacterTable(\Model\Identity\User $user, \Model\Game\Character $current) {
        
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::AVATAR(),
            \I18n::IDENTITY(),
            \I18n::CONSTANTS(),
            \I18n::POSITION()
        )) ;
        
        foreach (\Model\Game\Character::GetFromUser($user) as $char) {
            if ($char->equals($current)) {
                $act = "" ;
            } else {
                $act = "<br/>" . new \Quantyl\XML\Html\A("/Game/Character/TakeControl?id={$char->id}", \I18n::CHARACTER_TAKE_CONTROL()) ;
            }
            
            $table->addRow(array(
                $char->getImage("mini"),
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()) . "<br/>" .
                $char->race->getName() . " - " . $char->sex->getName() . "<br/>" .
                \I18n::LEVEL() . " : " . $char->level . "<br/>" .
                \I18n::METIERS() . " : " . $char->getHonorary() .
                $act,
                \I18n::TIME() . " : " . $char->getTime() . "<br/>" .
                \I18n::ENERGY() . " : " . $char->getEnergy() . "<br/>" .
                \I18n::HYDRATION() . " : " . $char->getHydration() . "<br/>" .
                \I18n::HEALTH() . " : " . $char->health,
                $char->position->getName()
            )) ;
        }
        
        return $table ;
    }
    
}
