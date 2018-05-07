<?php

namespace Answer\Widget\Help\Characteristic;

class Character extends \Answer\Widget\Misc\Section {

    public function __construct(\Model\Game\Characteristic $c, $char, $classes = "") {
        $msg = "" ;
        if ($char === null) {
            $msg .= \I18n::EXP_SELECT_CHARACTER() ;
        } else {
            $carac = \Model\Game\Characteristic\Character::getValue($char, $c);
            $msg .= \I18n::HELP_CHARACTERISTIC_LEVELUP($carac, $c->id, 1, $char->point);
        }

        parent::__construct(\I18n::HELP_CHARACTER(), "", "", $msg, $classes);
    }

}
