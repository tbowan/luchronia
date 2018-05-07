<?php

namespace Widget\Hof ;

class Main extends \Quantyl\Answer\Widget {
    
    public function getGlobal() {
        
    }
    
    public function createTable($metiers) {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::METIER(),
            \I18n::MEDAL(),
            \I18n::CHARACTER()
        )) ;
        
        foreach ($metiers as $m) {
            $c = $m->getTheBestCharacter() ;
            if ($c == null) {
                $table->addRow(array(
                    new \Quantyl\XML\Html\A("/Hof/Metier?metier={$m->id}", $m->getName()),
                    $m->getMedalImg(null, "icone-med"),
                    \I18n::NONE()
                )) ;
            } else {
                $table->addRow(array(
                    new \Quantyl\XML\Html\A("/Hof/Metier?metier={$m->id}", $m->getName()),
                    $m->getMedalImg($c, "icone-med"),
                    new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", $c->getName())
                )) ;
            }
        }
        return $table ;
    }
    
    public function addMinistry(\Model\Game\Politic\Ministry $ministry) {
        $res = "<h2>" . $ministry->getName() . "</h2>" ;
        
        $table = $this->createTable(\Model\Game\Skill\Metier::getFromMinistry($ministry)) ;
        if ($table->getRowsCount() == 0) {
            return "" ;
        } else {
            return $res . $table ;
        }
    }
    
    public function getMetiers() {
        $res = "" ;
        foreach (\Model\Game\Politic\Ministry::GetAll() as $ministry) {
            $res .= $this->addMinistry($ministry) ;
        }
        
        return $res ;
    }
    
    public function getContent() {
        return ""
                . $this->getGlobal()
                . $this->getMetiers()
                ;
    }
    
}
