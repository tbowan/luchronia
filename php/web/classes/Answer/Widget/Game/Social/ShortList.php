<?php

namespace Answer\Widget\Game\Social ;

abstract class ShortList extends \Answer\Widget\Misc\Section {
    
    public function __construct($head, $more, $meta, \Model\Game\Character $me, $classes = "") {
        
        $list    = $this->getList($me) ;
        $content = $this->makeList($list) ;
        
        parent::__construct($head, $more, $meta, $content, $classes);
    }
    
    public abstract function getList(\Model\Game\Character $me) ;
    
    public function makeList($list) {
        $res = "" ;
        
        foreach ($list as $character) {
            $res .= "<div class=\"thumb\">" ;
            $res .= "<a href=\"/Game/Character/Show?id={$character->id}\">" ;
            $res .= $character->getImage("mini") ;
            $res .= "</a>" ;
            $res .= "</div>" ;
        }
        return $res ;
    }
    
    
    
}
