<?php

namespace Answer\Widget\Game\Notifications ;

class FriendRequest extends Base {
    
    public function __construct(\Model\Game\Character $c) {
        $requests = array() ;
        
        foreach (\Model\Game\Social\Request::GetFromB($c) as $req) {
            $requests[] = "<li>" . \I18n::_date($req->date - DT) . " " . new \Quantyl\XML\Html\A("/Game/Character/Friend/Accept?id={$req->id}", $req->a->getName()) . "</li>" ;
        }
        
        $res = \I18n::HINT_SOCIAL_REQUESTS(count($requests)) ;
        $res .= "<ul>" ;
        $res .= implode("", $requests) ;
        $res .= "</ul>" ;
        
        parent::__construct(
                \I18n::NOTIFICATION_FRIEND_REQUEST(),
                $res,
                "notice");
    }

}
