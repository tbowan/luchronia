<?php

namespace Services\Game\Post ;

class Reroute extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("parcel", new \Quantyl\Form\Model\Id(\Model\Game\Post\Parcel::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->parcel->recipient->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if ($this->parcel->destination->equals($this->getCharacter()->position)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if (! $this->getCharacter()->position->hasTownHall()) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    private $_curpos ;
    
    public function init() {
        parent::init();
        
        $p0 = $this->parcel->origin->getCoordinate() ;
        $p1 = $this->parcel->destination->getCoordinate() ;
        
        $dt = $this->parcel->tf - $this->parcel->t0 ;
        $t = (time() - $this->parcel->t0) / $dt ;
        
        $slerp = new \Quantyl\Misc\Slerp($p0, $p1) ;
        $pos = $slerp->interpolate($t) ;
        
        $this->_curpos = \Model\Game\City::GetClosest($this->parcel->origin->world, $pos, 2) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::REROUTE_MESSAGE(
                $this->_curpos->id,
                $this->_curpos->getName())) ;
        $form->addInput("destination", new \Form\Post\Destination($this->getCharacter(), $this->_curpos, \I18n::DESTINATION())) ;
    }
    
    public function getCost() {
        $cost = null ;
        foreach (\Model\Game\Building\Instance::GetFromCityAndJob($this->parcel->source, \Model\Game\Building\Job::GetByName("Post")) as $instance) {
            $post = \Model\Game\Building\Post::GetFromInstance($instance) ;
            if ($cost === null || $cost > $post->cost_parcel) {
                $cost = $post->cost_parcel ;
            }
        }
        return floatval($cost) ;
    }
    
    public function getQtty() {
        $qtty = 0 ;
        foreach(\Model\Game\Post\Good::GetFromParcel($this->parcel) as $good) {
            $qtty += $good->amount ;
        }
        return $qtty ;
    }
    
    public function onProceed($data) {
        $dist = \Model\Game\City::GetDist($this->_curpos, $data["destination"]) ;
        $cost = $dist * $this->getQtty() * $this->getCost() ;
        
        $me = $this->getCharacter() ;
        
        if ($me->getCredits() < $cost) {
            throw new \Exception(\I18n::EXP_SENDPARCEL_CANNOT_AFFORD($cost, $cost, 0)) ;
        }
        
        $me->addCredits(- $cost) ;
        $me->update() ;
        $this->parcel->source->addCredits($cost) ;
        
        $this->parcel->t0 = time() ;
        $this->parcel->tf = $this->parcel->t0 + $dist * 3600 / 20 ;
        $this->parcel->origin = $this->_curpos ;
        $this->parcel->destination = $data["destination"] ;
        $this->parcel->update() ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Post/Parcel?parcel={$this->parcel->id}")) ;
    }
    
    
}
