<?php

namespace Widget\View ;

class ConnectedMenu extends \Quantyl\Answer\Widget {
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $char) {
        $this->_char = $char ;
    }
    
    public function getContent() {
        $char = $this->_char ;
        
        $res = "" ;
        $res .= "<h1>" . $char->getName(). "</h1>" ;
        
        $res .= $char->getImage("med", "avatar") ;
        
        $res .= "<ul>" ;
        if ($char->locked) {
            $res .= " <li><strong>" . \I18n::CHARACTER_LOCK_STATE() . " :</strong> " . \I18n::CHARACTER_LOCKED() . "</li>" ;
        }
        $res .= " <li><strong>" . \I18n::TIME() . " :</strong> " . $char->getTime() . "</li>" ;
        $res .= " <li><strong>" . \I18n::ENERGY() . " :</strong> " . $char->getEnergy() . "</li>" ;
        $res .= " <li><strong>" . \I18n::HYDRATION() . " :</strong> " . $char->getHydration() . "</li>" ;
        
        $res .= " <li><strong>" . \I18n::LEVEL() . " :</strong> " . $char->level . " (" . $char->point . ") " ;
        $img = new \Quantyl\XML\Html\Img("/Media/icones/misc/Level.png", \I18n::LEVELUP()) ;
        $img->setAttribute("class", "icone") ;
        $res .= new \Quantyl\XML\Html\A("/Game/LevelUp", $img) ;
        $res .= "</li>" ;
        
        $res .= " <li><strong>" . \I18n::CREDITS() . " :</strong> " . $char->getCredits() . "</li>" ;
        $res .= " <li><a href=\"/User\">" . \I18n::USER_ACCOUNT() . "</a></li>" ;
        $res .= " <li><a href=\"/User/Logout\">" . \I18n::LOGOUT() . "</a></li>" ;
        $res .= "</ul>" ;
        
        return $res ;
    }
    
}
