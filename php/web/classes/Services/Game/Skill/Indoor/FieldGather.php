<?php

namespace Services\Game\Skill\Indoor ;

use Model\Game\Ressource\Inventory;

class FieldGather extends Base {

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);

        $field = \Model\Game\Building\Field::GetFromInstance($this->inst) ;
 
        $form->addMessage(\I18n::SKILL_FIELDGATHER_MSG(
            $field->amount - 1,
            $field->item->getName()
            )) ;
    }
    
    public function getField() {
        return \Model\Game\Building\Field::GetFromInstance($this->inst) ;
    }
    
    public function getToolInput() {
        return new \Form\Tool\FieldGather($this->cs, $this->getComplete(), $this->getField()) ;
    }
    
    public function getAmount($munition) {
        return $this->inst->level * $this->cs->level * parent::getAmount($munition);
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $fieldskill = \Model\Game\Skill\Field::GetFromSkill($this->cs->skill) ;
        $fieldinst  = $this->getField() ;
        
        if ($fieldinst->item != $fieldskill->item) {
            throw new \Quantyl\Exception\Http\ClientForbidden(
                    \I18n::SKILL_FIELDGATHER_ITEMDIFFERS(
                        $fieldskill->item->getName(),
                        $fieldinst->item->getName()
                    ) );
        }
    }
    
    
    public function doStuff($potential, $data) {
        
        // shortcuts to get elements
        $fieldskill = \Model\Game\Skill\Field::GetFromSkill($this->cs->skill) ;
        $fieldinst  = $this->getField() ;
        
        // amounts
        $available = $fieldinst->amount - 1 ;
        
        if ($available < $potential) {
            throw new \Exception(\I18n::EXP_FIELD_NOT_ENOUGH($potential, $available)) ;
        }
        
        $gained = $potential ;
        
        // give to character
        $rest = Inventory::AddItem($this->cs->character, $fieldskill->gain, $gained) ;
        $msg = \I18n::SKILL_FIELDGATHER_NORMAL($gained - $rest, $fieldskill->gain->getName()) ;
        
        if ($rest > 0) {
            $me = $this->cs->character ;
            $city = $me->position ;
            $item = $fieldskill->gain ;
            \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
            $msg .= \I18n::INVENTORY_FULL_GIVE_CITY(
                    $rest, $item->getName(),
                    $city->id, $city->id, $city->getName()
                    ) ;
        }
        
        // remove from field
        $fieldinst->amount -= $gained ;
        $fieldinst->update() ;
        
        $msg .= parent::doStuff($potential, $data) ;
        return $msg ;
    }
 

    public function getSpecificFieldSet() {
        return null ;
    }


}
