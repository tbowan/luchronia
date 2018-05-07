<?php
namespace Answer\View\Help\Characteristic ;

class ShowOne extends \Answer\View\Base {
    
    private $_carac ;
    private $_viewer ;
    
    public function __construct($service, \Model\Game\Characteristic $carac, $viewer) {
        parent::__construct($service) ;
        $this->_carac = $carac ;
        $this->_viewer = $viewer ;
        
    }
    
    public function getTplContent() {
        $res = "" ;
        $res .= new \Answer\Widget\Help\Characteristic\Description($this->_carac, $this->_viewer, "card-1-2") ;
        $res .= new \Answer\Widget\Help\Characteristic\Character($this->_carac, $this->_viewer, "card-1-2");
        $res .= new \Answer\Widget\Help\Characteristic\Skills($this->_carac, $this->_viewer);
        return $res ;
    }
    
    
    public function getTitle() {
        return \I18n::TITLE_HELP($this->_carac->getName()) ;
    }
    
}
