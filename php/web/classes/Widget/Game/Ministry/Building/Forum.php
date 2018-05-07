<?php


namespace Widget\Game\Ministry\Building ;

class Forum extends Base {
    
    public function getModerators(\Model\Game\Forum\Category $category) {
        $res = "" ;
        foreach (\Model\Game\Forum\Moderator::getFromCategory($category) as $modo) {
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$modo->moderator->id}", $modo->moderator->getName())
                    . " "
                    . new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/DelModerator?moderator={$modo->id}", \I18n::DELETE())
                    . "<br/>" ;
        }
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/AddModerator?category={$category->id}", \I18n::ADD_MODERATOR()) ;
        return $res ;
    }
    
    public function getActions(\Model\Game\Forum\Category $c) {
        $res = "" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/CategoryPrev?category={$c->id}", \I18n::MOVEBEFORE()) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/CategoryNext?category={$c->id}", \I18n::MOVEAFTER()) ;
        $res .= " | " ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/CategoryEdit?category={$c->id}", \I18n::EDIT()) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/CategoryDelete?category={$c->id}", \I18n::DELETE()) ;
        return $res ;
    }
    
    public function getSpecific() {
        
        $res = "<h2>" . \I18n::FORUM_TITLE_CATEGORIES() . "</h2>" ;
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CATEGORY(),
            \I18n::THREADS(),
            \I18n::POSTS(),
            \I18n::MODERATORS(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Forum\Category::GetFromInstance($this->_instance) as $category) {
            
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Forum/Category?category={$category->id}", $category->getName()) . "<br/>" . $category->description,
                \Model\Game\Forum\Thread::CountFromCategory($category),
                \Model\Game\Forum\Post::CountFromCategory($category),
                $this->getModerators($category),
                $this->getActions($category)
                        
            )) ;
        }
        $res .= $table ;
        
        $res .= "<p>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Building/Forum/AddCategory?instance={$this->_instance->id}", \I18n::ADD_FORUM_CATEGORY()) ;
        $res .= "</p>" ;
        return $res ;
    }
    
}
