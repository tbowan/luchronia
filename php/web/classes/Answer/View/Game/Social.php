<?php

namespace Answer\View\Game ;

class Social extends \Answer\View\Base {
    
    private $_character ;
    
    public function __construct($service, \Model\Game\Character $me) {
        parent::__construct($service) ;
        $this->_character = $me ;
    }
    
    public function getTplContent() {
        return ""
            . $this->getMessages()
            . $this->getFriendship() ;
    }
    
    public function getMessages() {
        return "<div class=\"card-1-2\">"
            . new \Answer\Widget\Game\Social\Mailboxes($this->_character)
            . new \Answer\Widget\Game\Social\GlobSub($this->_character)
            . new \Answer\Widget\Game\Social\CitySub($this->_character)
            . "</div>" ;
    }
    
    public function getFriendship() {
        return "<div class=\"card-1-2\">"
            . new \Answer\Widget\Game\Social\LongFriend($this->_character)
            . new \Answer\Widget\Game\Social\ShortFollower($this->_character)
            . new \Answer\Widget\Game\Social\ShortFollowing($this->_character)
            . "</div>" ;
    }
    
}
