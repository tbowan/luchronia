<?php

namespace Answer\Widget\Game\Building ;

class LibraryStock extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $character, $classes = "") {
        parent::__construct(\I18n::LIBRARY_STOCK(), "", "", $this->getStockList($instance, $character), $classes);
    }
    
    public function getStockList(\Model\Game\Building\Instance $instance, $character) {
        $res = "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Ressource\City::GetFromInstance($instance) as $st) {
            if ($st->published) {
                $res .= "<li><div class=\"item\">" ;
                $res .= "<div class=\"icon\">" . $st->item->getImage() . "</div>" ;
                $res .= "<div class=\"description\">"
                        . "<div class=\"name\">" . $st->item->getName() . "</div>"
                        . "<div class=\"price\">" . \I18n::PRICE() . " : " . $st->price . "</div>"
                        . "<div class=\"links\">" . $this->getLinks($st, $character) . "</div>"
                        . "</div>" ;
                $res .= "</div></li>" ;
            }
        }
        $res .= "</ul>" ;
        return $res ;
    }
        
    private function getLinks(\Model\Game\Ressource\City $stock, $character) {
        if ($character == null) {
            return "" ;
        }
        
        $parch = \Model\Game\Ressource\Parchment::GetByItem($stock->item) ;
        $book  = \Model\Game\Ressource\Book::GetByItem($stock->item) ;
        
        if ($parch != null) {
            $learn  = \Model\Game\Skill\Learn::GetFromCharacteristic($parch->skill->characteristic) ;
            $cs     = \Model\Game\Skill\Character::GetFromCharacterAndSkill($character, $learn->skill) ;
            return new \Quantyl\XML\Html\A("/Game/Skill/Indoor/Learn/Parchment?cs={$cs->id}&inst={$stock->instance->id}&stock={$stock->id}", \I18n::ITEM_LEARN()) ;
        } else if ($book != null) {
            $learn  = \Model\Game\Skill\Learn::GetFromCharacteristic($book->skill->characteristic) ;
            $cs     = \Model\Game\Skill\Character::GetFromCharacterAndSkill($character, $learn->skill) ;
            return new \Quantyl\XML\Html\A("/Game/Skill/Indoor/Learn/Book?cs={$cs->id}&inst={$stock->instance->id}&stock={$stock->id}", \I18n::ITEM_LEARN()) ;
        }
    }
    
}
