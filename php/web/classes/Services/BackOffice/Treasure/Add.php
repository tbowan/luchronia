<?php

namespace Services\BackOffice\Treasure ;

class Add extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), "", false)) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::TRASURE_ADD_MESSAGE()) ;
        
        $conditions = $form->addInput("cond", new \Quantyl\Form\FieldSet(\I18n::TREASURE_CONDITIONS())) ;
        $conditions->addInput("job", new \Quantyl\Form\Model\Select(\Model\Game\Building\Job::getBddTable(), \I18n::BUILDING_JOB())) ;
        $conditions->addInput("type", new \Quantyl\Form\Model\Select(\Model\Game\Building\Type::getBddTable(), \I18n::BUILDING_TYPE())) ;
        $conditions->addInput("biome", new \Quantyl\Form\Model\Select(\Model\Game\Biome::getBddTable(), \I18n::BIOME())) ;
        
        $item = $form->addInput("item", new \Quantyl\Form\FieldSet(\I18n::TREASURE_ITEM())) ;
        $item->addInput("item", new \Quantyl\Form\Model\Select(\Model\Game\Ressource\Item::getBddTable(), \I18n::ITEM())) ;
        $item->addInput("amount", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT())) ;
        $item->addInput("infinite", new \Quantyl\Form\Fields\Boolean(\I18n::INFINITE())) ;
        $item->addInput("gained", new \Quantyl\Form\Fields\Float(\I18n::GAIN())) ;
    }
    
    public function onProceed($data) {
        
        \Model\Game\Ressource\Treasure::createFromValues(array(
            "job"       => $data["cond"]["job"],
            "type"      => $data["cond"]["type"],
            "biome"     => $data["cond"]["biome"],
            "city"      => $this->city,
            "item"      => $data["item"]["item"],
            "amount"    => $data["item"]["amount"],
            "infinite"  => $data["item"]["infinite"],
            "gained"    => $data["item"]["gained"],
        )) ;
        
    }
}
