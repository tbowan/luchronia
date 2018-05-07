<?php

namespace Answer\Widget\Game\Notifications ;

class Locked extends Notification {
    
    public function __construct() {
        parent::__construct(
                \I18n::LOCKED(),
                \I18n::HINT_IS_LOCKED(),
                "critical");
    }
    
}
