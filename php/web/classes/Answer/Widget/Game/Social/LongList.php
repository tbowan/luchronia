<?php

namespace Answer\Widget\Game\Social ;

abstract class LongList extends \Answer\Widget\Misc\Section {
    
    public function __construct($head, $more, $meta, $classes = "") {
        
        $list    = $this->getList() ;
        $content = $this->makeList($list) ;
        
        parent::__construct($head, $more, $meta, $content, $classes);
    }
    
    public abstract function getList() ;
    
    public function makeList($list) {
        $res = "<ul class=\"itemList\">" ;
        
        foreach ($list as $character) {
            $res .= new CharacterAsItem($character) ;
        }
        $res .= "</ul>" ;
        return $res ;
    }
    

}
