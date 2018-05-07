<?php

namespace Services\BackOffice ;

class Main extends \Services\Base\Admin {
    
    public function getView() {
        // TODO
        return new \Quantyl\Answer\Message(\I18n::LM_BACKOFFICE() . \I18n::BACKOFFICE_MAIN_CONTENT());
    }
    
}
