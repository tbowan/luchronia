<?php

namespace Services\Game\Skill\Indoor ;

use Model\Game\Ressource\Inventory;
use Model\Game\Skill\In;
use Model\Game\Skill\Out;
use Quantyl\XML\Html\Table;

class Secondary extends Base {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Check character has ins
        foreach (In::GetFromSkill($this->cs->skill) as $i) {
            $a = Inventory::GetAmount($this->cs->character, $i->item) ;
            if ($a < $i->amount) {
                throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_SKILL_SECONDARY_NEED_MORE($i->item->getName(), $i->amount)) ;
            }
        }
    }
    
    public function getSecondaryMessage() {
        $this->cs ;
        
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::NEEDED(),
            \I18n::PRODUCED(),
            \I18n::INVENTORY_AFTER()
        )) ;
        
        foreach (In::GetFromSkill($this->cs->skill) as $i) {
            $a = Inventory::GetAmount($this->cs->character, $i->item) ;
            $table->addRow(array(
                $i->item->getImage("icone-med") . " " . $i->item->getName(),
                $i->amount,
                "-",
                $a - $i->amount
            )) ;
        }
        
        foreach (Out::GetFromSkill($this->cs->skill) as $i) {
            $a= Inventory::GetAmount($this->cs->character, $i->item) ;
            $table->addRow(array(
                $i->item->getImage("icone-med") . $i->item->getName(),
                "-",
                $i->amount,
                $a + $i->amount
            )) ;
        }
        
        $res = \I18n::SKILL_SECONDARY_INFORMATION_MSG() . $table ;
        
        return $res ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $form->addMessage($this->getSecondaryMessage()) ;
    }
    
    public function getToolInput() {
        return new \Form\Tool\Secondary($this->cs, $this->getComplete()) ;
    }
    
    public function getTime($tool) {
        return round(parent::getTime($tool) / $this->cs->level) ;
    }
    
    public function doStuff($points, $data) {
        
        $char = $this->getCharacter() ;
        $msg  = "" ;

        // Replace ins by outs
        foreach (In::GetFromSkill($this->cs->skill) as $i) {
            Inventory::DelItem($char, $i->item, $i->amount) ;
        }
        
        foreach (Out::GetFromSkill($this->cs->skill) as $i) {
            $rest = Inventory::AddItem($char, $i->item, $i->amount) ;
            if ($rest > 0) {
                $me = $this->getCharacter() ;
                $city = $me->position ;
                $item = $i->item ;
                \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
                $msg .= \I18n::INVENTORY_FULL_GIVE_CITY(
                        $rest, $item->getName(),
                        $city->id, $city->id, $city->getName()
                        ) ;
            }
        }
        
        $msg .= \I18n::SKILL_SECONDARY_NORMAL_MESSAGE() ;
        $msg .= parent::doStuff($points, $data);
        return $msg ;
    }
    

    


}
