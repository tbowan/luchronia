<?php

namespace Services\BackOffice\Forum ;

use I18n;
use Model\I18n\Lang;
use Quantyl\Answer\ListAnswer;
use Quantyl\XML\Html\A;
use Widget\BackOffice\Forum\CategoryTree;

class Main extends \Services\Base\Admin{
    
    public function getView() {
        $lang = Lang::GetCurrent() ;
        
        $answer = new ListAnswer() ;
        $answer->addAnswer(new CategoryTree($lang)) ;
        $answer->addAnswer(new A("/BackOffice/Forum/AddCategory", I18n::ADD())) ;
        
        return $answer ;
    }
    
}
