<?php

namespace Answer\View ;

class Wiki extends Base {
    
    private $_page ;
    private $_isadmin ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, $page, $isadmin) {
        parent::__construct($service);
        $this->_page    = $page ;
        $this->_isadmin = $isadmin ;
    }
    
    public function getTplContent() {
        $res = "" ;
        $res .= new \Answer\Widget\Misc\Section($this->_page->title, "", $this->getWikiMeta(), $this->_page->content, "card-2-3 left") ;
        $res .= new \Answer\Widget\Misc\Section(\I18n::WIKI_INDEX_TITLE(), "", "", \I18n::WIKI_INDEX_CONTENT(), "card-1-3 right") ;
        
        return $res ;
    }

    public function getWikiMeta() {
        $res = "" ;
        
        $res .= \I18n::WIKI_I18N_AVAILABLE() . " : " ;
        $links = array() ;
        foreach ($this->_page->getEquiv() as $p) {
            $links[] = new \Quantyl\XML\Html\A("http://" . $p->lang->dns . "/Wiki/" . urlencode($p->title), $p->lang->getImage("icone-inline")) ;
        }
        $res .= implode(" ", $links) ;
        
        if ($this->_isadmin) {
            $res .= " / " ;
            $res .= \I18n::ADMINISTER()
                    . " : "
                    . new \Quantyl\XML\Html\A("/BackOffice/Wiki/Edit?id={$this->_page->id}", \I18n::EDIT())
                    . ", "
                    . new \Quantyl\XML\Html\A("/BackOffice/Wiki/Delete?id={$this->_page->id}", \I18n::Delete()) ;
        }
        return $res ;
    }

}
