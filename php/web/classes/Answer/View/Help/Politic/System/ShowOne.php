<?php


namespace Answer\View\Help\Politic\System ;

class ShowOne extends \Answer\View\Base {
    
    private $_systemtype ;
    
    public function __construct($service, \Model\Game\Politic\SystemType $st) {
        parent::__construct($service) ;
        $this->_systemtype = $st ;
    }
    
    public function getTplContent() {
        
        $res = "" ;
        $res .= "<div class=\"card-1-3\">" . $this->_systemtype->getImage("full") . "</div>" ;
        $res .= "<div class=\"card-2-3\">" . $this->_systemtype->getDescription() . "</div>" ;
        
        $more = new \Quantyl\XML\Html\A("/Help/Politic/System", \I18n::HELP_SEE_SYSTEMS()) ;
        
        return new \Answer\Widget\Misc\Section($this->_systemtype->getName(), $more, "", $res, "") ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP($this->_systemtype->getName()) ;
    }
}
