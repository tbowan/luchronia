<?php

namespace Services\Game\Skill\Indoor ;

use Model\Game\Building\Instance;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Model\Game\Skill\Character;

class Learn extends \Services\Base\Character {
      
    public function fillParamForm(Form &$form) {
        $form->addInput("cs", new Id(Character::getBddTable())) ;
        $form->addInput("inst", new Id(Instance::getBddTable(), "", false)) ;
    }
    
    public function addStock($characteristic, $stock, &$choices) {
        
        $prev = "/Game/Skill/Indoor/Learn/" ;
        $next = "?cs={$this->cs->id}&inst={$this->inst->id}&stock={$stock->id}" ;
        
        // Book ?
        $book = \Model\Game\Ressource\Book::GetByItem($stock->item) ;
        if ($book !== null) {
            if ($characteristic->equals($book->skill->characteristic)) {
                $choices["Book-{$stock->id}"] = $stock->item->getName() ;
            }
            return ;
        }
        
        // Parchment ?
        $parchment = \Model\Game\Ressource\Parchment::GetByItem($stock->item) ;
        if ($parchment !== null) {
            if ($characteristic->equals($parchment->skill->characteristic)) {
                $choices["Parchment-{$stock->id}"] = $stock->item->getName() ;
            }
            return ;
        }
        
        // Else : nothing
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $skill          = $this->cs->skill ;
        $learning       = \Model\Game\Skill\Learn::GetFromSkill($skill) ;
        $characteristic = $learning->characteristic ;
        
        $choices = array() ;
        foreach (\Model\Game\Ressource\City::GetFromInstance($this->inst) as $stock) {
            $this->addStock($characteristic, $stock, $choices) ;
        }
        
        $form->addMessage(\I18n::SKILL_INDOOR_LEARN_MESSAGE()) ;
        $form->addInput("stock", new \Quantyl\Form\Fields\Select(\I18n::ITEM()))
             ->setChoices($choices) ;
        
    }
    
    public function onProceed($data, $form) {
        $table = explode("-", $data["stock"]) ;
        $type = $table[0] ;
        $stock = $table[1] ;
        
        $this->setAnswer(new Redirect("/Game/Skill/Indoor/Learn/{$type}?cs={$this->cs->id}&inst={$this->inst->id}&stock={$stock}")) ;
    }
    
    
}
