<?php

namespace Services\Game\Ministry\Building\Forum ;

class AddCategory extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Must be a forum
        $job = \Model\Game\Building\Job::GetByName("Forum") ;
        if (! $job->equals($this->instance->job)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_FORUM_ISNOT()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("title",        new \Quantyl\Form\Fields\Text(\I18n::TITLE())) ;
        $form->addInput("description",  new \Quantyl\Form\Fields\Text(\I18n::DESCRIPTION())) ;
        $form->addInput("rw",           new \Quantyl\Form\Model\EnumSimple(\Model\Game\Forum\Access::getBddTable(), \I18n::ACCESS(), true)) ;
    }

    public function onProceed($data) {
        
        \Model\Game\Forum\Category::createFromValues(array(
                "instance"      => $this->instance,
                "title"         => $data["title"],
                "description"   => $data["description"],
                "rw"            => $data["rw"]
                )) ;
        
    }

public function getCity() {
    return $this->instance->city ;
}

public function getMinistry() {
    return $this->instance->job->ministry ;
}

}
