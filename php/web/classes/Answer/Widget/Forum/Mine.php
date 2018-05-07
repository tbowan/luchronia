<?php

namespace Answer\Widget\Forum ;

class Mine extends ThreadTable {
    
    public function __construct(\Model\Game\Character $me) {
        parent::__construct(\Model\Forum\Thread::GetMine($me), $me);
    }
    
}
