<?php

namespace Widget\BackOffice\Forum ;

class CategoryTree extends \Quantyl\Answer\Widget {
    
    private $_lang ;
    
    public function __construct(\Model\I18n\Lang $lang) {
        $this->_lang = $lang ;
    }
    
    private function addRow(\Quantyl\XML\Html\Table &$table, $prefix, \Model\Forum\Category $cat) {
        $table->addRow(array(
            $prefix . $cat->title,
            $cat->description,
            $cat->view_group !== null ? $cat->view_group->name : "-",
            $cat->moderate_group !== null ? $cat->moderate_group->name : "-",
            count($cat->getThreads()),
            "<a href=\"/BackOffice/Forum/EditCategory?id={$cat->id}\">". \I18n::EDIT() . "</a>" .
            " <a href=\"/BackOffice/Forum/DeleteCategory?id={$cat->id}\">". \I18n::DELETE() . "</a>" .
            " |" .
            " <a href=\"/BackOffice/Forum/CategoryMoveUp?id={$cat->id}\">". \I18n::MOVEUP() . "</a>" .
            " <a href=\"/BackOffice/Forum/CategoryMoveBefore?id={$cat->id}\">". \I18n::MOVEBEFORE() . "</a>" .
            " <a href=\"/BackOffice/Forum/CategoryMoveAfter?id={$cat->id}\">". \I18n::MOVEAFTER() . "</a>" .
            " <a href=\"/BackOffice/Forum/CategoryMoveDown?id={$cat->id}\">". \I18n::MOVEDOWN() . "</a>" 
            
        ));
        foreach ($cat->getChildren() as $child) {
            $this->addRow($table, "$prefix&#160;&#160;&#160;&#160;", $child) ;
        }
    }
    
    
    public function getContent() {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
           \I18n::CATEGORY(),
            \I18n::DESCRIPTION(),
            \I18n::VIEW_GROUP(),
            \I18n::MODERATE_GROUP(),
            \I18n::THREADS(),
            \I18n::ACTIONS()
        )) ;
        
        $categories = \Model\Forum\Category::GetRoots($this->_lang) ;
        foreach ($categories as $cat) {
            $this->addRow($table, "", $cat) ;
        }
        
        return "$table" ;
    }
    
    
}
