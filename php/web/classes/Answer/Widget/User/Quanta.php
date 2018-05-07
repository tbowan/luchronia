<?php

namespace Answer\Widget\User ;

class Quanta extends \Answer\Widget\Misc\Section  {
    
    public function __construct(\Model\Identity\User $u, $classes = "") {
        parent::__construct(\I18n::USER_QUANTA() , "", "", \I18n::USER_QUANTA_MESSAGE($u->quanta), $classes);
    }
    
}
