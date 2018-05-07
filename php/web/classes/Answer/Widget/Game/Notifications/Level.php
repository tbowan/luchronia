<?php

namespace Answer\Widget\Game\Notifications ;

class Level extends Base {
    
    public function __construct(\Model\Game\Character $c) {
        parent::__construct(
                \I18n::LEVEL(),
                \I18n::HINT_LEVEL_POINT($c->point),
                "notice");
    }

}

