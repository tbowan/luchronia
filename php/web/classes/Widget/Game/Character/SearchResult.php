<?php

namespace Widget\Game\Character ;

class SearchResult extends \Quantyl\Answer\Widget {
    
    private $_search ;
    
    public function __construct($search) {
        $this->_search = $search ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::CHARACTER_LIST() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::AVATAR(),
            \I18n::IDENTITY(),
            \I18n::INFORMATIONS(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Character::GetBySearch($this->_search) as $c) {
            $table->addRow(array(
                $c->getImage("mini"),
                $c->getName(),
                $c->race->getName() . " " . $c->sex->getName() . " " . \I18n::LEVEL() . " " . $c->level,
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", \I18n::SEE())
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
