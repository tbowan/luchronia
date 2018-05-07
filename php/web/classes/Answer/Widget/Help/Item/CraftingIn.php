<?php

namespace Answer\Widget\Help\Item ;

class CraftingIn extends Crafting {
    
    public function getTable($item, $viewer) {
        $table = parent::getTable($item, $viewer);
        $this->addPrimary($item, $viewer, $table) ;
        $this->addFieldOut($item, $viewer, $table) ;
        $this->addSecondaryOut($item, $viewer, $table) ;
        return $table ;
    }

    public function getSectionTitle() {
        return \I18n::CRAFTING_IN() ;
    }

}
