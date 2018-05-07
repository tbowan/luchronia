<?php

namespace Answer\Widget\Help\Item ;

class Description extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Ressource\Item $item, $classes = "") {
        $res = "" ;
        $res .= $this->_getIllustration($item) ;
        $res .= $this->_getDescription($item) ;
        parent::__construct( $item->getName(), "", "", $res, $classes) ;
    }
    
    private function _getIllustration(\Model\Game\Ressource\Item $item) {
        return "<div class=\"card-1-3\">" . $item->getImage() . "</div>" ;
    }
    
    private function _getDescription(\Model\Game\Ressource\Item $item) {
        $msg = ""
                . $this->_getBiome($item)
                . $this->_getEatable($item)
                . $this->_getDrinkable($item)
                . $this->_getMap($item)
                . $this->_getParchment($item)
                . $this->_getBook($item)
                . $this->getTool($item)
                . $this->getMunition($item)
                . $this->getModifier($item)
                ;
        
        if ($msg == "") {
            $msg = \I18n::HELP_ITEM_NO_MSG() ;
        }
        return "<div class=\"card-2-3\">$msg</div>" ;
    }
    
    private function _getBiome(\Model\Game\Ressource\Item $item) {
        $list = "" ;
        foreach (\Model\Game\Ressource\Ecosystem::GetFromItem($item) as $eco) {
            $cf = "(" . $eco->min . " - " . $eco->max . ")" ;
            if ($eco->biome === null) {
                $list .= "<li>" . new \Quantyl\XML\Html\A("/Help/Biome", \I18n::BIOME_ALL()) . " $cf</li>" ;
            } else {
                $list .= "<li>" . new \Quantyl\XML\Html\A("/Help/Biome?id={$eco->biome->id}", $eco->biome->getName()) . " $cf</li>" ;
            }
        }
        
        if ($list != "") {
            $res = "<h2>" . \I18n::HELP_NATURAL() . "</h2>" ;
            $res .= \I18n::HELP_NATURAL_MESSAGE() ;
            $res .= "<ul>$list</ul>" ;
            return $res ;
        } else {
            return "" ;
        }
    }
    
    private function _getEatable(\Model\Game\Ressource\Item $item) {
        $eatable = \Model\Game\Ressource\Eatable::GetByItem($item) ;
        if ($eatable === null) {
            return "" ;
        } else if ($eatable->race === null) {
            return \I18n::HELP_EATABLE_MESSAGE(\I18n::ALL(), $eatable->energy);
        } else {
            return \I18n::HELP_EATABLE_MESSAGE($eatable->race->getName(), $eatable->energy);
        }
    }
    
    private function _getDrinkable(\Model\Game\Ressource\Item $item) {
        $drinkable = \Model\Game\Ressource\Drinkable::GetByItem($item) ;
        if ($drinkable != null) {
            return \I18n::HELP_DRINKABLE_MESSAGE($drinkable->hydration, $drinkable->energy) ;
        } else {
            return "" ;
        }
    }
    
    private function _getMap(\Model\Game\Ressource\Item $item) {
        $m = \Model\Game\Building\Map::getFromItem($item) ;
        
        if ($m === null) {
            return "" ;
        } else {
            $res = "<h2>" . \I18n::BUILDING_MAP() . "</h2>" ;
            $res .= "<ul>" ;
            $res .= "<li><strong>" . \I18n::BUILDING_JOB()  . " : </strong> " . new \Quantyl\XML\Html\A("/Help/Building/Job?id={$m->job->id}",   $m->job->getName()) . "</li>" ;
            $res .= "<li><strong>" . \I18n::BUILDING_TYPE() . " : </strong> " . new \Quantyl\XML\Html\A("/Help/Building/Type?id={$m->type->id}", $m->type->getName()). "</li>" ;
            $res .= "<li><strong>" . \I18n::LEVEL()         . " : </strong> " . $m->level . "</li>" ;
            $res .= "<li><strong>" . \I18n::BUILDING_TECH() . " : </strong> " . $m->tech . "</li>" ;
            
            $cst = $m->getCosts() ;
            $cst_msg = "<ul>" ;
            foreach ($cst as $id => $c) {
                $item = \Model\Game\Ressource\Item::GetById($id) ;
                $cst_msg .= "<li>" . $c . " " . $item->getImage("icone-inline") . " " . new \Quantyl\XML\Html\A("/Help/Item?id={$item->id}", $item->getName()) . "</li>" ;
            }
            $cst_msg .= "</ul>" ;
            
            $res .= "<li><strong>" . \I18n::BUILDING_COST() . " : </strong> $cst_msg</li>" ;
            
            $res .= "</ul>" ;
            return $res ;
        }
    }
    
    public function _getParchment(\Model\Game\Ressource\Item $item) {
        $parch = \Model\Game\Ressource\Parchment::GetByItem($item) ;
        if ($parch === null) {
            return "" ;
        } else {
            $skill = $parch->getLearningSkill() ;

            $res = "<h2>" . \I18n::PARCHMENT() . "</h2>" ;
            $res .= \I18n::HELP_ITEM_PARCHMENT_MESSAGE() ;

            $res .= "<ul>" ;
            $res .= "<li><strong>" . \I18n::LEARNING_SKILL() . " : </strong> "
                    . $skill->getImage("icone") . " " . new \Quantyl\XML\Html\A("/Help/Skill?id={$skill->id}", $skill->getName())
                    . "</li>" ;
            $res .= "<li><strong>" . \I18n::LEARNED_SKILL() . " : </strong> "
                    . $parch->skill->getImage("icone") . " " . new \Quantyl\XML\Html\A("/Help/Skill?id={$parch->skill->id}", $parch->skill->getName())
                    . "</li>" ;
            $res .= "</ul>" ;
            return $res ;
        }
    }

    public function _getBook(\Model\Game\Ressource\Item $item) {
        $book = \Model\Game\Ressource\Book::GetByItem($item) ;
        if ($book === null) {
            return "" ;
        } else {
            $skill = $book->getLearningSkill() ;
        
            $res = "<h2>" . \I18n::BOOK() . "</h2>" ;
            $res .= \I18n::HELP_ITEM_BOOKMESSAGE() ;
            $res .= "<ul>" ;
            $res .= "<li><strong>" . \I18n::LEARNING_SKILL() . " : </strong> "
                    . $skill->getImage("icone") . " " . new \Quantyl\XML\Html\A("/Help/Skill?id={$skill->id}", $skill->getName())
                    . "</li>" ;
            $res .= "<li><strong>" . \I18n::LEARNED_SKILL() . " : </strong> "
                    . $book->skill->getImage("icone") . " " . new \Quantyl\XML\Html\A("/Help/Skill?id={$book->skill->id}", $book->skill->getName())
                    . "</li>" ;
            $res .= "</ul>" ;
            return $res ;
        }
    }
    
    public function getTool(\Model\Game\Ressource\Item $item) {
        $res = "<h2>" . \I18n::HELP_ITEM_TOOL_TITLE() . "</h2>" ;
        $res .= \I18n::HELP_ITEM_TOOL_MESSAGE() ;
        $res .= "<ul>" ;
        $has = false ;
        foreach (\Model\Game\Skill\Tool::GetFromItem($item) as $tool) {
            $has = true ;
            $res .= "<li>" . $tool->skill->getImage("icone-inline") . " " ;
            $res .= new \Quantyl\XML\Html\A("/Help/Skill?id={$tool->skill->id}", $tool->skill->getName()) ;
            $res .= "</li>" ;
        }
        $res .= "</ul>" ;
        if ($has) {
            return $res ;
        } else {
            return "" ;
        }
    }
    
    public function getMunition(\Model\Game\Ressource\Item $item) {
        $res = "<h2>" . \I18n::HELP_ITEM_MUNITION_TITLE() . "</h2>" ;
        $res .= \I18n::HELP_ITEM_MUNITION_MESSAGE() ;
        $res .= "<ul>" ;
        $has = false ;
        foreach (\Model\Game\Ressource\Munition::GetByMunition($item) as $munition) {
            $has = true ;
            $res .= "<li>" . $munition->tool->skill->getImage("icone-inline") . " " ;
            $res .= new \Quantyl\XML\Html\A("/Help/Skill?id={$munition->tool->skill->id}", $munition->tool->skill->getName()) ;
            $res .= "</li>" ;
        }
        $res .= "</ul>" ;
        if ($has) {
            return $res ;
        } else {
            return "" ;
        }
    }
    
    public function getModifier(\Model\Game\Ressource\Item $item) {
        $msg = "" ;
        foreach (\Model\Game\Ressource\Modifier::GetByItem($item) as $mod) {
            $msg .= new \Answer\Widget\Game\Ressource\ModifierCard($mod->modifier) ;
        }
        return $msg ;
    }
    
}
