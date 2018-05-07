<?php

namespace Answer\Widget\Forum ;

class Last extends ThreadTable {
    
    public function __construct(\Model\Game\Character $me) {
        parent::__construct(\Model\Forum\Thread::GetUnread($me->previous), $me);
    }
    
}
