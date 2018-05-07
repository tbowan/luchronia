<?php


namespace Answer\View\Help\Politic\Ministry ;

class ShowOne extends \Answer\View\Base {
    
    private $_ministry ;
    
    public function __construct($service, \Model\Game\Politic\Ministry $m) {
        parent::__construct($service) ;
        $this->_ministry = $m ;
    }
    
    public function getDescription() {
        
        $res = "" ;
        $res .= "<div class=\"card-1-3\">" . $this->_ministry->getImage("full") . "</div>" ;
        $res .= "<div class=\"card-2-3\">" . $this->_ministry->getDescription() . "</div>" ;
        
        $more = new \Quantyl\XML\Html\A("/Help/Politic/Ministry", \I18n::HELP_SEE_MINISTRIES()) ;
        
        return new \Answer\Widget\Misc\Section($this->_ministry->getName(), $more, "", $res) ;
        
    }
    
    public function getBuildings() {
        
        $res = "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Building\Job::GetFromMinistry($this->_ministry) as $b) {
            $res .= "<li class=\"card-1-4\"><div class=\"item\">"
                    . new \Quantyl\XML\Html\A("/Help/Building/Job?id={$b->id}", $b->getName())
                    . "</div></li>" ;
        }
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::BUILDING_LIST(), "", "", $res) ;

    }
    
    public function getTplContent() {
        return ""
                . $this->getDescription()
                . $this->getBuildings() ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP($this->_ministry->getName()) ;
    }
}
