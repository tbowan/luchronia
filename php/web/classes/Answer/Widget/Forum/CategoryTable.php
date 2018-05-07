<?php

namespace Answer\Widget\Forum ;

class CategoryTable extends \Quantyl\Answer\Widget {
    
    private $_categories ;
    private $_me ;
    
    public function __construct($categories, \Model\Game\Character $me) {
        parent::__construct();
        $this->_categories = $categories ;
        $this->_me = $me ;
    }
    
    private function getLastThread($last) {
        if ($last !== null) {
            $mst_content  = "<a href=\"/Forum/Thread?id={$last->thread->id}\">" . $last->thread->title . "</a><br/>" ;
            $mst_content .= \I18n::_date_time($last->date - DT) . " - " ;
            $mst_content .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$last->author->id}", $last->author->getName()) ;
        } else {
            $mst_content = "" ;
        }
        return $mst_content ;
    }
    
    private function addCat(&$table, \Model\Forum\Category $cat) {
        $last = $cat->getLastPost() ;
        $row = $table->addRow(array(
            $cat->getImage("icone-med"),
            "<a href=\"/Forum/Category?id={$cat->id}\">" .
            $cat->title . "</a><br/>{$cat->description}" ,
            $cat->getThreadCount() ,
            $cat->countPost() ,
            $this->getLastThread($last)
        )) ;
        if ($last != null && $last->date > $this->_me->previous) {
            $row->setAttribute("class", "unread") ;
        }
    }
    
    public function getContent() {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::CATEGORY(),
            \I18n::THREADS(),
            \I18n::MESSAGES(),
            \I18n::LAST_THREAD()
        )) ;
        
        foreach ($this->_categories as $cat) {
            $this->addCat($table, $cat) ;
        }
        
        return "" . $table ;
    }
    
}
