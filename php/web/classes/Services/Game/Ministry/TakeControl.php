<?php

namespace Services\Game\Ministry ;

use Form\MapChoser;
use Model\Game\Building\Instance;
use Model\Game\Building\Job;
use Model\Game\Building\Map;
use Model\Game\Building\Need;
use Model\Game\Building\Site;
use Model\Game\Building\TownHall;
use Model\Game\Politic\Monarchy;
use Model\Game\Politic\System;
use Model\Game\Politic\SystemType;
use Model\Game\Ressource\Item;
use Quantyl\Answer\Message;
use Quantyl\Exception\Http\ClientForbidden;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Request\Request;

class TakeControl extends \Services\Base\Character {

    public function fillParamForm(Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $city = $this->city ;
        
        if (! $city->equals($this->getCharacter()->position)) {
            throw new ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        } else if ($city->hasTownHall()) {
            // TODO Add Message
            throw new ClientForbidden(\I18n::EXP_TAKECONTROL_ALREADY_TOWNHALL()) ;
        }
        
        $sys = System::LastFromCity($city) ;
        $anarchy = SystemType::Anarchy() ;
        if (! $anarchy->equals($sys->type)) {
            // TODO Add Message
            throw new ClientForbidden(\I18n::EXP_TAKECONTROL_ALREADY_SYSTEM()) ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->addMessage(\I18n::TAKE_CONTROL_MESSAGE()) ;
        $form->addInput("map", new MapChoser($this->getCharacter(), \I18n::BUILDING_MAP()))
             ->SetJob(Job::GetByName("TownHall"));
        $form->addInput("name", new Text(\I18n::CITY_NAME())) ;
        $form->addInput("welcome", new FilteredHtml(\I18n::CITY_WELCOME())) ;
        return $form ;
    }

    public function onProceed($data, $form) {
        $inventory  = $data["map"] ;
        $map        = Map::getFromItem($inventory->item) ;
        $character  = $this->getCharacter() ;
        $city       = $character->position ;
        
        $instance = Instance::createFromValues(array(
            "job"       => Job::GetByName("Site") ,
            "type"      => $map->type,
            "city"      => $city,
            "level"     => $map->level,
            "health"    => 10
        )) ;
        
        $site = Site::createFromValues(array(
            "instance"      => $instance,
            "job"           => $map->job,
            "last_update"   => time()
        )) ;

        
        foreach ($map->getCosts() as $id => $amount) {
            $item = Item::GetById($id) ;
            Need::createFromValues(array(
                "site"      => $site,
                "item"      => $item,
                "needed"    => $amount,
                "provided"  => 0
            )) ;
        }
        
        $inventory->amount -= 1 ;
        $inventory->update() ;
        
        TownHall::createFromValues(array(
                "instance" => $instance,
                "name"     => $data["name"],
                "welcome"  => $data["welcome"]
                )) ;
        
        // Create monarchy
        $system = System::createFromValues(array(
            "city" => $city,
            "type" => SystemType::Monarchy(),
            "start" => time(),
        )) ;

        $monarchy = Monarchy::createFromValues(array(
            "system" => $system,
            "king" => $character
        )) ;
        
        \Model\Game\Citizenship::createFromValues(array(
                "character" => $character,
                "city"      => $city,
                "created"   => time(),
                "from"      => time(),
                "isInvite"  => false
                )) ;
        
        $this->setAnswer(new Message(\I18n::TAKE_CONTROL_DONE($city->getName()))) ;
        
    }

}
