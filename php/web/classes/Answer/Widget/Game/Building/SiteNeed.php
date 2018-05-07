<?php

namespace Answer\Widget\Game\Building ;

class SiteNeed extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $isminister, $classes = "") {
        $site = \Model\Game\Building\Site::GetFromInstance($instance) ;
        
        $res = "" ;
        $res .= $site->job->getDescription() ;
        $res .= $this->getNeed($site, $isminister) ;
        
        
        parent::__construct(
                \I18n::BUILDING_SITE_INFORMATION(),
                "",
                "",
                $res,
                $classes);
    }
    
    public function getNeed(\Model\Game\Building\Site $site, $isminister) {
        $res = "<ul class=\"itemList\">" ;
        
        $needs = \Model\Game\Building\Need::GetFromSite($site) ;
        foreach ($needs as $n) {
            $res .= "<li><div class=\"item\">" ;
            $res .=  $this->getIcon($n) ;
            $res .= "<div class=\"description\">"
                    . $this->getName($n)
                    . $this->getNeedValue($n)
                    . $this->getLinks($n, $isminister)
                    . "</div>" ;
            $res .= "</div></li>" ;
        }
        $res .= "</ul>" ;
        return $res ;
    }
    
    private function getIcon(\Model\Game\Building\Need $n) {
        return "<div class=\"icon\">" . $n->item->getImage() . "</div>" ;
    }
    
    private function getName(\Model\Game\Building\Need $n) {
        return "<div class=\"name\">" . $n->item->getName() . "</div>" ;
    }
    
    private function getNeedValue(\Model\Game\Building\Need $n) {
        return "<div class=\"need\">"
                . \I18n::REMAIN_NEEDED() . " : "
                . $n->provided . " / "
                . $n->needed . " "
                . new \Quantyl\XML\Html\Meter(0, $n->needed, $n->provided)
                . "</div>" ;
    }
    
    private function getLinks(\Model\Game\Building\Need $n, $isminister) {
        $act  = "" ;
        $act .= new \Quantyl\XML\Html\A("/Game/Building/ProvideNeeded?need={$n->id}", \I18n::PROVIDE()) ;
        if ($isminister) {
            $act .= new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/GrantNeeded?need={$n->id}", \I18n::GRANT_NEEDED()) ;
        }
        
        return "<div class=\"links\">" . $act . "</div>" ;
    }
}
