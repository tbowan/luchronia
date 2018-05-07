<?php

namespace Services ;

use \Quantyl\Service\EnhancedService ;


class Main extends EnhancedService {

    public function getView() {
        $lang = \Model\I18n\Lang::GetCurrent() ;
        return new \Answer\View\Main($this, $lang) ;
    }

}
