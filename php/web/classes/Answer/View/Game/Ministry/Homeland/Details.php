<?php


namespace Answer\View\Game\Ministry\Homeland ;

class Details extends \Answer\View\Base {
    
    private $_citizenship ;
    private $_isMyself    ;
    private $_isMinister  ;
    
    public function __construct($service, $citizenship, $myself, $isminister) {
        parent::__construct($service);
        $this->_citizenship = $citizenship ;
        $this->_isMyself    = $myself ;
        $this->_isMinister  = $isminister  ;
    }
    
    public function getCitizenDetail($classes = "") {
        $c = $this->_citizenship->character ;
        $v = $this->getService()->getCharacter() ;
        $m = $c->equals($v) ;
        
        return new \Answer\Widget\Game\Character\IdentityCard($c, $v, $m, $classes) ;
    }
    
    public function getCitizenshipDetail($classes) {
        $res = "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY()          . " :</strong> " . new \Quantyl\XML\Html\A("/Game/City?id={$this->_citizenship->city->id}", $this->_citizenship->city->getName()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::CREATED()       . " :</strong> " . \I18n::_date_time($this->_citizenship->created - DT) . "</li>" ;
        $res .= "<li><strong>" . \I18n::CITIZEN_FROM()  . " :</strong> " . ($this->_citizenship->from !== null ? \I18n::_date_time($this->_citizenship->from - DT) : "-") . "</li>" ;
        $res .= "<li><strong>" . \I18n::CITIZEN_TO()    . " :</strong> " . ($this->_citizenship->to   !== null ? \I18n::_date_time($this->_citizenship->to   - DT) : "-") . "</li>" ;
        $res .= "</ul>" ;

        $more = "" ;
        if ($this->_isMinister && ! $this->_citizenship->isInvite) {
            if ($this->_citizenship->from === null) {
                $more .= new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Answer?citizenship={$this->_citizenship->id}", \I18n::ANSWER()) ;
            } else {
                $more .= new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Deprive?citizenship={$this->_citizenship->id}", \I18n::DEPRIVE()) ;
            }
        } else if ($this->_isMyself && $this->_citizenship->isInvite) {
            $more .= new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Answer?citizenship={$this->_citizenship->id}", \I18n::ANSWER()) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::CITIZENSHIP(), $more, "", $res, $classes) ;
        
    }
    
    
    public function addMessage($msg) {
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $msg->character->getImage("mini") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-right\">" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-center\">" ;
            $res .= "<div class=\"post-head\">" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$msg->character->id}", $msg->character->getName()) ;
            $res .= " - " ;
            $res .= \I18n::_date_time($msg->date - DT)  ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"post-content\">" ;
            $res .= $msg->message ;
            $res .= "</div>" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"clear\"></div>" ;
        
        $res .= "</div>" ;
        return $res ;
    }
    
    public function getMessages($classes = "") {
        $res = "" ;
        foreach (\Model\Game\Citizenship\Message::GetFromCitizenship($this->_citizenship) as $msg) {
            $res .= $this->addMessage($msg) ;
        }
        
        $more = new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/AddMessage?citizenship={$this->_citizenship->id}", \I18n::ADD_MESSAGE()) ;
        
        return new \Answer\Widget\Misc\Section(\I18n::MESSAGES(), $more, "", $res, $classes) ;
    }
    
    public function getTplContent() {
        return ""
                . $this->getCitizenDetail("card-1-2")
                . $this->getCitizenshipDetail("card-1-2")
                . $this->getMessages()
                ;
    }
    
}
