<?php

namespace Answer\Widget\Forum ;

class ThreadTable extends \Quantyl\Answer\Widget {
    
    private $_threads ;
    private $_me ;
    
    public function __construct($threads, \Model\Game\Character $me) {
        parent::__construct() ;
        $this->_threads = $threads ;
        $this->_me      = $me ;
    }

    private function getIcon(\Model\Forum\Thread $th) {
        return $th->getImages("icone-med") ;
    }
    
    private function getLastContent($last) {
        if ($last != null) {
            $last_content = ""
                    . \I18n::_date_time($last->date - DT)
                    . "<br/>"
                    . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$last->author->id}", $last->author->getName());
        } else {
            $last_content = "" ;
        }
        return $last_content ;
    }
    
    private function addThread(&$table, \Model\Forum\Thread $th) {
        $last = $th->getLastPost() ;
        $row = $table->addRow(array(
            $this->getIcon($th),
            " <a href=\"/Forum/Thread?id={$th->id}\">" . $th->title . "</a><br/>"
            . \I18n::BY() . " : " . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$th->author->id}", $th->author->getName()),
            $th->countPost(),
            $th->viewed,
            $this->getLastContent($last)
        )) ;
        if ($last != null && $last->date > $this->_me->previous) {
            $row->setAttribute("class", "unread") ;
        }
    }
    
    public function getContent() {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->setAttribute("class", "forum") ;
        
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::THREAD(),
            \I18n::MESSAGES(),
            \I18n::VIEWED(),
            \I18n::LAST_MESSAGE()
                )) ;

        foreach ($this->_threads as $t) {
            $this->addThread($table, $t) ;
        }
        
        return "" . $table ;
    }

}
