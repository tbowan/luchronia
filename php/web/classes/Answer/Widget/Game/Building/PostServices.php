<?php

namespace Answer\Widget\Game\Building ;

class PostServices extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $classes = "") {
        
        $post = \Model\Game\Building\Post::GetFromInstance($instance) ;
        
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::SEND_MESSAGE() . " : </strong>"
                . new \Quantyl\XML\Html\A("/Game/Post/ChoseRecipient?instance={$instance->id}&next=%2FGame%2FPost%2FCompose", \I18n::RECIPIENT_SINGLE())
                . ", "
                . new \Quantyl\XML\Html\A("/Game/Post/ComposeGroup?post={$post->id}", \I18n::RECIPIENT_GROUP())
                . ".</li>" ;
        $res .= "<li><strong>" . \I18n::SEND_PARCEL() . " : </strong>"
                . new \Quantyl\XML\Html\A("/Game/Post/ChoseRecipient?instance={$instance->id}&next=%2FGame%2FPost%2FSendParcel%3Fpost={$post->id}", \I18n::RECIPIENT_SINGLE())
                . ".</li>" ;
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::POSTAL_SERVICES(), "", "", $res, $classes);
    }
    
}
