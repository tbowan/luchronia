<?php

namespace Answer\Widget\Game\Character ;

class CharacteristicSecondary extends \Answer\Widget\Misc\Section  {
    
    private function getSecondaryTable(\Model\Game\Character $c) {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTERISTIC(),
            \I18n::BASE_VALUE(),
            \I18n::BONUS_VALUE(),
            \I18n::TOTAL()
                )) ;
        
        foreach (\Model\Game\Characteristic::GetSecondary() as $cha) {
            $base = \Model\Game\Characteristic\Character::getValue($c, $cha) ;
            $bonus = 0
                    + \Model\Game\Characteristic\Character::getEquipableBonus($c, $cha)
                    + \Model\Game\Characteristic\Character::getModifierBonus($c, $cha) ;
            $table->addRow(array(
                $cha->getImage("icone-inline") . " " . $cha->getName() . " " . \I18n::HELP("/Help/Characteristic?id={$cha->id}"),
                $base - $bonus,
                ($bonus >= 0 ? " + " : " - ") . abs($bonus),
                $base
            )) ;
        }
        
        return "$table" ;
        
    }
    
    public function __construct(\Model\Game\Character $c, $classes) {
        
        parent::__construct(\I18n::SECONDARY_CHARACTERISTICS(), \I18n::HELP_MSG("/Help/Characteristic"), "", $this->getSecondaryTable($c), $classes) ;
    }

}
