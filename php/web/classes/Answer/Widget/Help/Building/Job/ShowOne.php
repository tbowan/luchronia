<?php

namespace Answer\Widget\Help\Building\Job ;

class ShowOne extends \Quantyl\Answer\Widget {
    
    private $_job ;
    
    public function __construct(\Model\Game\Building\Job $j) {
        $this->_job = $j ;
    }
    
    public function getMaps() {
        $res = "" ;
        $maps = array() ;
        $types = array() ;
        foreach (\Model\Game\Building\Type::GetAll() as $t) {
            $maps[$t->id] = array() ;
            $types[$t->id] = $t ;
        }
        foreach (\Model\Game\Building\Map::getFromJob($this->_job) as $map) {
            $maps[$map->type->id][$map->level] = $map ;
        }
        
        $res .= \I18n::MAPS_AND_TECHNOLOGY_MESSAGE() ;
        $table = new \Quantyl\XML\Html\Table() ;
        
        $head = array("") ;
        for ($i=1; $i<=$this->_job->level; $i++) {
            $head[] = $i ;
        }
        $table->addHeaders($head) ;
        
        foreach ($maps as $typeid => $rowmap) {
            $row = array(
                $types[$typeid]->getName()
                . " "
                . \I18n::HELP("/Help/Building/Type?id=" . $typeid)
                    ) ;
            foreach ($rowmap as $map) {
                $row[] = new \Quantyl\XML\Html\A("/Help/Item?id={$map->item->id}", $map->tech) ;
            }
            $table->addRow($row) ;
        }
        $res .= $table ;
        return $res ;
    }
    
    public function getSkills() {
        $res = "<h2>" . \I18n::SKILL_LIST() . "</h2>" ;
        
        $hasskill = false ;
        $res .= "<ul>" ;
        foreach (\Model\Game\Skill\Skill::getFromJob($this->_job) as $sk) {
            $res .= "<li>"
                    . $sk->getImage("icone")
                    . " " . $sk->getName()
                    . " " . \I18n::HELP("/Help/Skill?id={$sk->id}")
                    . "</li>" ;
            $hasskill = true ;
        }
        $res .= "</ul>" ;
        
        return ($hasskill ? $res : "") ;
    }
    
    public function getContent() {
        $res = "" ;
        $res .= "<h2>" . \I18n::DESCRIPTION() . "</h2>" ;
        $res .= \I18n::HELP_BUILDING_JOB_MESSAGE() ;
        $res .= $this->_job->getDescription() ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::MINISTRY() . " : </strong> " . $this->_job->ministry->getName() . " " . \I18n::HELP("/Help/Politic/Ministry?id={$this->_job->ministry->id}") . "</li>" ;
        $res .= "<li><strong>" . \I18n::HEALTH() . " : </strong> " . $this->_job->health . "</li>" ;
        $res .= "<li><strong>" . \I18n::MAX_LEVEL() . " : </strong> " . $this->_job->level . "</li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_RESISTANCE() . " : </strong> " . number_format(100 * $this->_job->wear, 2) . " %</li>" ;
        $res .= "</ul>" ;
        
        $res .= "<h2>" . \I18n::MAPS_AND_TECHNOLOGY() . "</h2>" ;
        if ($this->_job->level == 0) {
            $res .= \I18n::HELP_BUILDING_JOB_NO_MAP() ;
        } else {
            $res .= $this->getMaps() ;
        }
        
        $res .= $this->getSkills() ;
        return $res ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP($this->_job->getName()) ;
    }
    
}
