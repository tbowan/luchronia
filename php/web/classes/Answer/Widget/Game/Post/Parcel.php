<?php

namespace Answer\Widget\Game\Post ;

class Parcel extends \Quantyl\Answer\Widget{
    
    private $_parcel ;
    private $_me ;
    
    public function __construct(\Model\Game\Post\Parcel $parcel, \Model\Game\Character $me) {
        parent::__construct();
        $this->_parcel = $parcel ;
        $this->_me = $me ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::PARCEL_ROUTING() . "</h2>" ;
        $res .= $this->getRouting() ;
        
        $res .= "<h2>" . \I18n::PARCEL_DETAILS() . "</h2>" ;
        if ($this->_parcel->tf < time() && $this->_parcel->destination->equals($this->_me->position)) {
            $res .= $this->getDetails() ;
        } else {
            $res .= $this->getNoDetails() ;
        }
        return $res ;
    }
    
    public function getRouting() {
        $res = "<ul>" ;
        $res .= "<li><strong>" . \I18n::SENDER() . " : </strong>" . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$this->_parcel->sender->id}", $this->_parcel->sender->getName()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::SOURCE() . " : </strong>" . new \Quantyl\XML\Html\A("/Game/City?id={$this->_parcel->source->id}", $this->_parcel->source->getName()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::PARCEL_T0() . " : </strong>" . \I18n::_date_time($this->_parcel->t0 - DT) . "</li>" ;
        $res .= "<li><strong>" . \I18n::DESTINATION() . " : </strong>" . new \Quantyl\XML\Html\A("/Game/City?id={$this->_parcel->destination->id}", $this->_parcel->destination->getName()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::PARCEL_TF() . " : </strong>" . \I18n::_date_time($this->_parcel->tf - DT) . "</li>" ;
        
        if (! $this->_parcel->destination->equals($this->_me->position) && $this->_me->position->hasTownHall()) {
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Post/Reroute?parcel={$this->_parcel->id}", \I18n::PARCEL_REROUTE()) . "</li>" ;
        }
        $res .= "</ul>" ;
        
        return $res ;
    }
    
    public function addMessage() {
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $this->_parcel->sender->getImage("mini") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-center\">" ;
            $res .= "<div class=\"post-head\">" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$this->_parcel->sender->id}", $this->_parcel->sender->getName()) ;
            $res .= " - " ;
            $res .= \I18n::_date_time($this->_parcel->sended - DT)  ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"post-content\">" ;
            $res .= $this->_parcel->message ;
            $res .= "</div>" ;
        $res .= "</div>" ;
        
        $res .= "</div>" ;
        return $res ;
    }
    
    public function getDetails() {
        $res = "" ;
        if ($this->_parcel->message != "") {
            $res .= $this->addMessage() ;
        }
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT()
        )) ;
        
        if ($this->_parcel->credits > 0) {
            $table->addRow(array(\I18n::CREDITS(), $this->_parcel->credits)) ;
        }
        foreach (\Model\Game\Post\Good::GetFromParcel($this->_parcel) as $good) {
            $table->addRow(array($good->item->getName(), $good->amount)) ;
        }
        
        $res .= $table ;
        
        $res .= "<p>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Post/Open?parcel={$this->_parcel->id}", \I18n::OPEN_PARCEL()) ;
        $res .= "</p>" ;
        
        return $res ;
    }
    
    public function getNoDetails() {
        return \I18n::PARCEL_NODETAILS() ;
    }
    
    
}
