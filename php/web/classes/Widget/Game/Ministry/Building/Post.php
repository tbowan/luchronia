<?php

namespace Widget\Game\Ministry\Building ;

class Post extends Base {
    
    public function getTradingTax() {
        $post = \Model\Game\Building\Post::GetFromInstance($this->_instance) ;
        
        return new \Answer\Widget\Misc\Card(
                \I18n::POST_TAX(),
                \I18n::POST_TAX_MESSAGE(
                    $post->cost_mail,
                    $post->cost_parcel)
                    . new \Quantyl\XML\Html\A("/Game/Ministry/Building/Post/ChangeCost?post={$post->id}", \I18n::CHANGE_COST()
                )) ;
    }
    
    
}
