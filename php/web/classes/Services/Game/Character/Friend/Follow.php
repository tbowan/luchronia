<?php

namespace Services\Game\Character\Friend;

use Exception;
use Model\Game\Character;
use Model\Game\Social\Follower;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Follow extends \Services\Base\Character {

    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Character::getBddTable()));
    }

    public function checkPermission(Request $req) {
        parent::checkPermission($req);

        $me = $this->getCharacter() ;

        // TODO : better exception
        if ($this->id->equals($me)) {
            // Character are same
            throw new Exception();
        } else if (Follower::GetFromAB($me, $this->id) != null) {
            // Already connected
            throw new Exception();
        }
    }

    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::FOLLOW_MESSAGE(
                $this->id->getId(),
                $this->id->getName()
                )) ;
    }
    
    public function onProceed($data) {

        $fellower = Follower::createFromValues(array(
            "a" => $_SESSION["char"],
            "b" => $this->id,
            "date" => time()
        ));
        \Model\Event\Listening::Social_Fellow($fellower) ;
    }

}
