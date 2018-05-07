<?php

namespace Answer\Widget\Game\Notifications ;

class Commerce extends Base {
    
    public function __construct($ressources, $skill) {
        parent::__construct(
                \I18n::NOTIFICATION_COMMERCE_TITLE(),
                \I18n::NOTIFICATION_COMMERCE_MESSAGE(
                        $ressources,
                        $skill
                        ));
    }

    public function getClasses() {
        return parent::getClasses() . " notice" ;
    }
    
}
