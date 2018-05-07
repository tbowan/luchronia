<?php

namespace Services\BackOffice\I18n ;

use Model\I18n\Lang;
use Widget\BackOffice\I18n\LangList;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        $langs = Lang::getAll() ;
        return new LangList($langs) ;
    }
    
}