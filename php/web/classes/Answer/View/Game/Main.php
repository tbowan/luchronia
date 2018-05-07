<?php

namespace Answer\View\Game ;

class Main extends \Answer\View\Base {
    
    private $_character ;
    
    public function __construct($service, \Model\Game\Character $me) {
        parent::__construct($service);
        $this->_character = $me ;
    }
    
    private function getNotifications() {
        $res = "" ;
        
        if ($this->_character->locked) {
            $res .=  new \Answer\Widget\Game\Notifications\Locked();
        } else {
            
            if ($this->_character->health <= 0) {
                $res .= new \Answer\Widget\Game\Notifications\NearDeath($this->_character) ;
            }
            $res .= new \Answer\Widget\Game\Notifications\EnergyHydration($this->_character);

            
            if ($this->_character->point > 0) {
                $res .= new \Answer\Widget\Game\Notifications\Level($this->_character);
            }

            if (\Model\Game\Social\Request::HasFromB($this->_character)) {
                $res .= new \Answer\Widget\Game\Notifications\FriendRequest($this->_character) ;
            }
            
            $unread_mails = \Model\Game\Post\Inbox::CountUnreadFromCharacter($this->_character) ;
            $parcels      = \Model\Game\Post\Parcel::CountFromRecipient($this->_character) ;
            $following    = \Model\Game\Forum\Follow::CountNew($this->_character) ;
            if ($unread_mails + $parcels + $following > 0) {
                $res .= new \Answer\Widget\Game\Notifications\Post($unread_mails, $parcels, $following) ;
            }
            
            $ressources = \Model\Game\Trading\Character\Market\Sell::CountMyFull($this->_character) ;
            $skill      = \Model\Game\Trading\Skill::CountMyFull($this->_character) ;
            if ($ressources + $skill > 0) {
                $res .= new \Answer\Widget\Game\Notifications\Commerce($ressources, $skill) ;
            }
            
            $res .= \Answer\Widget\Game\Notifications\Citizenship::getNotif($this->_character) ;
        }
        
        return new \Answer\Widget\Misc\Section (
                \I18n::NOTIFICATIONS(),
                "",
                "",
                $res,
                "") ;
    }
    
    private function getPosition() {
        $city = $this->_character->position ;
        
        $msg = "" ;
        
        $msg .= "<ul class=\"itemList\">" ;
        $msg .= new \Answer\Widget\Game\City\OutdoorCard($city) ;
        foreach (\Model\Game\Building\Instance::GetFromCity($city) as $instance) {
            $msg .= "<li>" . new \Answer\Widget\Game\City\BuildingCard($instance, $instance->canManage($this->getCharacter())) . "</li>" ;
        }
        $msg .= "</ul>" ;
        
        
        return new \Answer\Widget\Misc\Section(
                \I18n::POSITION(),
                new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", \I18n::DETAILS()),
                $city->getGeocoord(),
                $msg,
                "card-1-2") ;
    }
    
    private function getNews() {
        
        $news = array() ;
        
        /* Append diary news */
        foreach (\Model\Game\Social\Post::getNews($this->_character) as $post) {
            if ($post->access->hasCharacterAccess($post->author, $this->_character)) {
                $date = $post->date ;
                if (! isset($news[$date])) {
                    $news[$date] = array() ;
                }
                $news[$date][] = new \Answer\Widget\Game\News\Diary($post) ;
            }
        }
        
        /* Sort all news */
        krsort($news) ;
        
        $res = "" ;
        $res .= "<ul class=\"itemList\">" ;
        foreach ($news as $date => $items) {
            foreach( $items as $it) {
                $res .= "<li>$it</li>" ;
            }
        }
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::NEWS(), "", "", $res, "card-1-2") ;
    }
    
    public function getTplContent() {
        return ""
                . $this->getNotifications()
                . $this->getNews()
                . $this->getPosition()
                ;
                
    }

}
