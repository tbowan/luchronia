<?php

namespace Answer\Widget\Game\Social ;

class ShortFollowing extends ShortList {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        parent::__construct(
                \I18n::FOLLOWING_LIST(),
                new \Quantyl\XML\Html\A("/Game/Social/Following?id={$me->id}", \I18n::DETAILS()),
                "",
                $me,
                $classes);
    }
    
    public function getList(\Model\Game\Character $me) {
        return $me->getFollowing() ;
    }

}
