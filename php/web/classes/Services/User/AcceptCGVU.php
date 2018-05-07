<?php

namespace Services\User ;

class AcceptCGVU extends \Services\Base\Connected {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $last = \Model\Identity\Cgvu::getLast() ;
        if ($last != null) {
            $user = $this->getUser() ;
            $accepted = \Model\Identity\Accepted::getAccepted($user, $last) ;
            if ($accepted != null) {
                // TODO : ça fait une erreur 500
                throw new \Exception(\I18n::CGVU_ALREADY_ACCEPTED(\I18n::_date_time($accepted->when))) ;
            }
            
            $form->setMessage(\I18n::CGVU_MESSAGE()) ;
            $form->addInput("accepted", new \Quantyl\Form\Fields\Boolean(\I18n::ACCEPT_CGVU($last->file), true)) ;
        } else {
            // TODO : ça fait une erreur 500
            throw new \Exception(\I18n::CGVU_NONE_ACCEPTABLE()) ;
        }
    }
    
    public function onProceed($data) {
        $last = \Model\Identity\Cgvu::getLast() ;
        \Model\Identity\Accepted::createFromValues(array(
            "user" => $this->getUser(),
            "cgvu" => $last,
            "ip" => $this->getRequest()->getServer()->getClientIp(),
            "when" => time()
        )) ;
    }

}
