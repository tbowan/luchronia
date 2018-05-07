<?php

namespace Services\Wiki ;

use Quantyl\Form\Form;
use Quantyl\Service\EnhancedService;
use Model\I18n\Lang ;

class Main extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("page", new \Quantyl\Form\Fields\Text()) ;
    }
    
    private $_p ;
    
    public function init() {
        parent::init();

        $lang = Lang::GetCurrent() ;
        
        if ($this->page == "") {
            $this->_p = $lang->wikipage ;
        } else {
            $this->_p = \Model\Wiki\Page::GetByLangAndName($lang, $this->page) ;
        }
    }
    
    public function isAdmin() {
        try {
            $acl = new \Quantyl\ACL\Admin($this->getRequest()->getServer()->getConfig()) ;
            $acl->checkPermission() ;
            return true ;
        } catch (\Exception $ex) {
            return false ;
        }
    }
    
    public function getView() {
        if ($this->_p == null) {
            $msg = \I18n::WIKI_PAGE_404() ;
            if ($this->isAdmin()) {
                $msg .= \I18n::WIKI_ADMIN_404($this->_title) ;
            }
            throw new \Quantyl\Exception\Http\ClientNotFound($msg) ;
            // return new \Answer\View\Wiki404($this, $this->page, $this->isAdmin()) ;
        } else {
            return new \Answer\View\Wiki($this, $this->_p, $this->isAdmin()) ;
        }
        
    }
    
    public function getTitle() {
        if ($this->_p === null) {
            return \I18n::TITLE_Services_Wiki_404() ;
        } else {
            return $this->_p->title ;
        }
    }
    
}
