<?php

namespace Services\Game\Forum ;

class Category extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("category", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Category::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if (! $this->category->canRW($char)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_FORUM_CATEGORY_FORBIDEN()) ;
        }
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Forum\Category($this->category) ;
    }
    
    public function getTitle() {
        if ($this->category != null ) {
            return \I18n::TITLE_GAME_FORUM_CATEGORY($this->category->getName()) ;
        } else {
            return parent::getTitle();
        }
    }
    
}
