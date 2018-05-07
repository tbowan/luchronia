<?php

namespace Answer\Widget\Game\Social ;

class ShortFriend extends ShortList {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        parent::__construct(
                \I18n::FRIENDS_LIST(),
                new \Quantyl\XML\Html\A("/Game/Social/Friends?id={$me->id}", \I18n::DETAILS())
                        . ", "
                        . new \Quantyl\XML\Html\A("/Game/Character/Friend/Search", \I18n::SEARCH_FRIEND()),
                "",
                $me,
                $classes);
    }
    
    public function getList(\Model\Game\Character $me) {
        return $me->getFriends() ;
    }

}
