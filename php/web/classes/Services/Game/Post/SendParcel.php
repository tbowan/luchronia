<?php

namespace Services\Game\Post ;

class SendParcel extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("recipient", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable())) ;
        $form->addInput("post", new \Quantyl\Form\Model\Id(\Model\Game\Building\Post::getBddTable())) ;
        $form->addInput("referer", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(
                "<p><strong>" . \I18n::RECIPIENT() . " : </strong>" 
                . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$this->recipient->id}", $this->recipient->getName())
                . ".</p>") ;
                
        $form->addInput("destination", new \Form\Post\Destination($this->recipient, $this->getCity(), $this->post->cost_parcel, \I18n::DESTINATION())) ;
        $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
        $form->addInput("credits", new \Quantyl\Form\Fields\Integer(\I18n::CREDITS())) ;
        $form->addInput("goods", new \Form\Post\Goods($this->getCharacter())) ;
        
        
    }
    
    public function onProceed($data) {
        
        $me = $this->getCharacter() ;
        
        $qtty = 0 ;
        foreach ($data["goods"] as $t) {
            $qtty += $t[1] ;
        }
        $dist = max(1, \Model\Game\City::GetDist($me->position, $data["destination"])) ;
        
        $cost = $dist * $qtty * $this->post->cost_parcel ;
        $need = $cost + $data["credits"] ;
        $delay = $dist * 3600.0 / 20.0 ; // 20 Km/h
        
        if ($data["credits"] < 0) {
            throw new \Exception(\I18n::EXP_CANNOT_SEND_NEGATIVE_CREDITS()) ;
        } else if ($me->getCredits() < $need) {
            throw new \Exception(\I18n::EXP_SENDPARCEL_CANNOT_AFFORD($need, $cost, $data["credits"])) ;
        }
        
        $me->addCredits(-$need) ;
        $me->update() ;
        $me->position->addCredits($cost) ;
        $now = time() ;
        
        $parcel = \Model\Game\Post\Parcel::createFromValues(array(
            "sender"        => $me,
            "source"        => $me->position,
            "recipient"     => $this->recipient,
            "destination"   => $data["destination"],
            "message"       => $data["message"],
            "credits"       => $data["credits"],
            "origin"        => $me->position,
            "t0"            => $now,
            "tf"            => $now + $delay,
            "sended"        => $now
        )) ;
        
        foreach ($data["goods"] as $t) {
            if ($t[1] > 0) {
                \Model\Game\Post\Good::createFromValues(array(
                    "parcel" => $parcel,
                    "item" => $t[0]->item,
                    "amount" => $t[1]
                )) ;

                $t[0]->amount -= $t[1] ;
                $t[0]->update() ;

                $qtty += $t[1] ;
            }
        }
        
        \Model\Event\Listening::Social_Parcel($parcel) ;        
        
        if ($this->referer != null) {
            $this->setAnswer(new \Quantyl\Answer\Redirect($this->referer)) ;
        }
        
    }

    public function getCity() {
        return $this->post->instance->city ;
    }

}
