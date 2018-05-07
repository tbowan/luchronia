<?php

namespace Answer\Widget\Game\News ;

class Diary extends Base {
    
    public function __construct(\Model\Game\Social\Post $post) {
        
        parent::__construct(
                new \Quantyl\XML\Html\Img("/Media/icones/misc/Character.png", \I18n::CHARACTER()),
                \I18n::_date_time($post->date - DT),
                \I18n::NEWS_DIARY(
                        new \Quantyl\XML\Html\A("/Game/Character/Show?id={$post->author->id}", $post->author->getName()), $post->author->id)
                        );
    }
    
}
