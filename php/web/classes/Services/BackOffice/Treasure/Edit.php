<?php

namespace Services\BackOffice\Treasure ;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Treasure::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::TRASURE_EDIT_MESSAGE()) ;
        
        $conditions = $form->addInput("cond", new \Quantyl\Form\FieldSet(\I18n::TREASURE_CONDITIONS())) ;
        $conditions->addInput("job", new \Quantyl\Form\Model\Select(\Model\Game\Building\Job::getBddTable(), \I18n::BUILDING_JOB()))
                   ->setValue($this->id->job);
        $conditions->addInput("type", new \Quantyl\Form\Model\Select(\Model\Game\Building\Type::getBddTable(), \I18n::BUILDING_TYPE()))
                   ->setValue($this->id->type);
        $conditions->addInput("biome", new \Quantyl\Form\Model\Select(\Model\Game\Biome::getBddTable(), \I18n::BIOME()))
                   ->setValue($this->id->biome);
        
        $item = $form->addInput("item", new \Quantyl\Form\FieldSet(\I18n::TREASURE_ITEM())) ;
        $item->addInput("item", new \Quantyl\Form\Model\Select(\Model\Game\Ressource\Item::getBddTable(), \I18n::ITEM()))
              ->setValue($this->id->item);
        $item->addInput("amount", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT()))
             ->setValue($this->id->amount);
        $item->addInput("infinite", new \Quantyl\Form\Fields\Boolean(\I18n::INFINITE()))
             ->setValue($this->id->infinite);
        $item->addInput("gained", new \Quantyl\Form\Fields\Float(\I18n::GAIN()))
             ->setValue($this->id->gained);
    }
    
    public function onProceed($data) {
        
        $this->id->job      = $data["cond"]["job"] ;
        $this->id->type     = $data["cond"]["type"] ;
        $this->id->biome    = $data["cond"]["biome"] ;
        
        $this->id->item     = $data["item"]["item"] ;
        $this->id->amount   = $data["item"]["amount"] ;
        $this->id->infinite = $data["item"]["infinite"] ;
        $this->id->gained   = $data["item"]["gained"] ;
        
        $this->id->update() ;
    }
    
}
