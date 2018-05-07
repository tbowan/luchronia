<?php

namespace Widget\Hof ;

class Metier extends \Quantyl\Answer\Widget {
    
    private $_metier ;
    
    public function __construct(\Model\Game\Skill\Metier $m) {
        $this->_metier = $m ;
    }
    
    public function getDescription() {
        $res  = $this->_metier->getMedalImg($_SESSION["char"], "left-illustr") ;
        $res .= "<h2>" . \I18n::DESCRIPTION() . "</h2>" ;
        $res .= $this->_metier->getDescription() ;
        $res .= new \Quantyl\XML\Html\A("/Help/Metier?id={$this->_metier->id}", \I18n::TITLE_Services_Help_Metier()) ;
        return $res ;
    }
    
    public function getGlobal() {
        $res = "<h2>" . \I18n::HOF_METIER_GLOBAL() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTER(),
            \I18n::MEDAL(),
            \I18n::LEVEL(),
            \I18n::USES()
        )) ;
        
        foreach ($this->_metier->getBestCharacter(10) as $c) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", $c->getName()),
                $this->_metier->getMedalImg($c, "icone-med", $c->uses),
                $this->_metier->getLevel($c, $c->uses),
                $c->uses
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
        
    }
    
    public function getMetiers() {
        
        $res = "" ;
        
        return $res ;
    }
    
    public function getContent() {
        return ""
                . $this->getDescription()
                . $this->getGlobal()
                . $this->getMetiers()
                ;
    }
    
}
