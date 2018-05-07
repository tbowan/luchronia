<?php

namespace Answer\Widget\Game\Notifications ;

class Post extends Base {
    
    public function __construct($messages, $parcels, $forum) {
        parent::__construct(
                \I18n::NOTIFICATION_POST_TITLE(),
                \I18n::NOTIFICATION_POST_MESSAGE(
                        $messages,
                        $parcels,
                        $forum
                        ),
                "notice");
    }
    
}
