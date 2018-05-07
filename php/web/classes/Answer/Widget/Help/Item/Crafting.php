<?php

namespace Answer\Widget\Help\Item ;

abstract class Crafting extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Ressource\Item $item, $viewer, $classes = "") {
        parent::__construct($this->getSectionTitle(), "", "", $this->getTable($item, $viewer), $classes);
    }
    
    public abstract function getSectionTitle() ;
    
    public function getTable($item, $viewer) {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SKILL(),
            \I18n::SKILL_IN(),
            \I18n::SKILL_OUT()
        )) ;
        return $table ;
    }
    
    public function skillToString($skill, $viewer) {
        $res = "" ;
        $res .= $skill->getImage("icone-med") ;
        $res .= new \Quantyl\XML\Html\A("/Help/Skill?id={$skill->id}", $skill->getName()) ;
        
        if ($viewer === null) {
            $res .= "" ;
        } else {
            $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($viewer, $skill) ;
            if ($cs === null || $cs->level == 0) {
                $res .= "" ;
            } else {
                $res .= " " . \I18n::LEVEL() . " : " . $skill->level ;
            }
        }
        return $res ;
    }
    
    public function listToString($elements) {
        $list = array() ;
        foreach ($elements as $e) {
            $list[] = ($e[0] != 1 ? $e[0] : "") . new \Quantyl\XML\Html\A("/Help/Item?id=" . $e[1]->id, $e[1]->getImage("icone-med")) ;
        }
        return implode(" + ", $list) ;
    }
    
    public function addRow($skill, $viewer, $ins, $outs, &$table) {
        $table->addRow(array(
            $this->skillToString($skill, $viewer),
            $this->listToString($ins),
            $this->listToString($outs)
            )) ;
    }
    
    public function addPrimary(\Model\Game\Ressource\Item $item, $viewer, &$table) {
        foreach (\Model\Game\Skill\Primary::GetFromOut($item) as $primary) {
            $this->addRow($primary->skill, $viewer, array(array(1, $primary->in)), array(array($primary->coef, $primary->out)), $table) ;
        }
    }
    
    public function addFieldOut(\Model\Game\Ressource\Item $item, $viewer, &$table) {
        foreach (\Model\Game\Skill\Field::GetFromGain($item) as $field) {
             $this->addRow($field->skill, $viewer, array(array(1, $field->item)), array(array(1, $field->gain)), $table) ;
        }
    }
    
    public function addFieldIn(\Model\Game\Ressource\Item $item, $viewer, &$table) {
        foreach (\Model\Game\Skill\Field::GetFromItem($item) as $field) {
             $this->addRow($field->skill, $viewer, array(array(1, $field->item)), array(array(1, $field->gain)), $table) ;
        }
    }
    
    public function addSecondary(\Model\Game\Skill\Skill $skill, $viewer, &$table) {
        $ins = array() ;
        $outs = array() ;
        foreach (\Model\Game\Skill\In::GetFromSkill($skill) as $in) {
            $ins[] = array($in->amount, $in->item) ;
        }
        foreach (\Model\Game\Skill\Out::GetFromSkill($skill) as $out) {
            $outs[] = array($out->amount, $out->item) ;
        }
        $this->addRow($skill, $viewer, $ins, $outs, $table) ;
    }
    
    public function addSecondaryOut(\Model\Game\Ressource\Item $item, $viewer, &$table) {
        foreach (\Model\Game\Skill\Out::GetFromItem($item) as $out) {
            $skill = $out->skill ;
            $this->addSecondary($skill, $viewer, $table) ;
        }
    }
    
    public function addSecondaryIn(\Model\Game\Ressource\Item $item, $viewer, &$table) {
        foreach (\Model\Game\Skill\In::GetFromItem($item) as $in) {
            $skill = $in->skill ;
            $this->addSecondary($skill, $viewer, $table) ;
        }
    }
    
}
