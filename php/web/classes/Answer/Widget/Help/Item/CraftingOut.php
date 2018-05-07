<?php

namespace Answer\Widget\Help\Item ;

class CraftingOut extends Crafting {
    
    public function getTable($item, $viewer) {
        $table = parent::getTable($item, $viewer);
        $this->addFieldIn($item, $viewer, $table) ;
        $this->addSecondaryIn($item, $viewer, $table) ;
        return $table ;
    }

    public function getSectionTitle() {
        return \I18n::CRAFTING_OUT() ;
    }

}
