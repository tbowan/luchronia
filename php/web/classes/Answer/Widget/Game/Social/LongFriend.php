<?php

namespace Answer\Widget\Game\Social ;

class LongFriend extends LongList {
    
    private $_me ;
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        $this->_me = $me ;
        parent::__construct(
                \I18n::FRIENDS_LIST(),
                new \Quantyl\XML\Html\A("/Game/Character/Friend/Search", \I18n::SEARCH_FRIEND()),
                "",
                $classes);
    }
    
    public function getList() {
        return $this->_me->getFriends() ;
    }

}
