<?php

namespace Services\User ;

class Notification extends \Services\Base\Character {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $user = $this->getUser() ;
        
        $fs1 = $form->addInput("social",    new \Quantyl\Form\FieldSet(\I18n::ONMAIL_SOCIAL())) ;
        $fs1->addInput("mail",              new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_SOCIAL_MAIL()))
            ->setValue($user->mailon_mail);
        $fs1->addInput("parcel",            new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_SOCIAL_PARCEL()))
            ->setValue($user->mailon_parcel);
        $fs1->addInput("friendship",        new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_SOCIAL_FRIENDSHIP()))
            ->setValue($user->mailon_friendship);
        $fs1->addInput("following",         new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_SOCIAL_FOLLOWING()))
            ->setValue($user->mailon_following);
        $fs1->addInput("wall",              new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_SOCIAL_WALL()))
            ->setValue($user->mailon_wall);      
        $fs1->addInput("follow",            new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_FORUM_FOLLOW()))
            ->setValue($user->mailon_forum_follow);
        
        $fs3 = $form->addInput("commerce",  new \Quantyl\Form\FieldSet(\I18n::ONMAIL_COMMERCE())) ;
        $fs3->addInput("item",              new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_COMMERCE_ITEM()))
            ->setValue($user->mailon_commerce_item);
        $fs3->addInput("skill",             new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_COMMERCE_SKILL()))
            ->setValue($user->mailon_commerce_skill);
        
        $fs3 = $form->addInput("news",  new \Quantyl\Form\FieldSet(\I18n::ONMAIL_NEWS())) ;
        $fs3->addInput("blog",              new \Quantyl\Form\Fields\Boolean(\I18n::ONMAIL_NEWS_BLOG()))
            ->setValue($user->mailon_blog);
        
    }
    
    public function onProceed($data) {
        
        $user = $this->getUser() ;
        
        $user->mailon_mail          = $data["social"]["mail"] ;
        $user->mailon_parcel        = $data["social"]["parcel"] ;
        $user->mailon_friendship    = $data["social"]["friendship"] ;
        $user->mailon_following     = $data["social"]["following"] ;
        $user->mailon_wall          = $data["social"]["wall"] ;      
        $user->mailon_forum_follow  = $data["social"]["follow"] ;
        
        $user->mailon_commerce_item     = $data["commerce"]["item"] ;
        $user->mailon_commerce_skill    = $data["commerce"]["skill"] ;
        
        $user->mailon_blog          = $data["news"]["blog"] ;
        
        $user->update() ;
        $_SESSION["user"] = $user ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("")) ;
    }
    
}
