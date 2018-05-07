<?php

namespace Answer\Widget\Game\Social ;

class LongFollower extends LongList {
    
    private $_me ;
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        $this->_me = $me ;
        parent::__construct(
                \I18n::FOLLOWERS_LIST(),
                "",
                "",
                $classes);
    }
    
    public function getList() {
        return $this->_me->getFollower() ;
    }

}
