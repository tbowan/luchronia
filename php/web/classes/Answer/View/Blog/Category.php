<?php

namespace Answer\View\Blog ;

class Category extends \Answer\Decorator\Blog {
    
    private $_category ;
    private $_perpage ;
    private $_page ;
    
    public function __construct(
            \Quantyl\Service\EnhancedService $service,
            \Model\Blog\Category $category,
            $page,
            $perpage) {
        parent::__construct($service, "");
        $this->_page = $page ;
        $this->_perpage = $perpage ;
        $this->_category = $category ;
    }
    
    public function getPageSelector() {
        $cnt = \Model\Blog\Post::CountLastFromCategory($this->_category) ;
        $max = ceil($cnt / $this->_perpage) ;
        
        $res = \I18n::PAGES() ;
        
        $res .= "<ul class=\"page-selector\">" ;
        for ($i=0; $i<$max; $i++) {
            $class = ($i == $this->_page) ? "class=\"current\"" : "" ;
            $res .= "<li $class>" . new \Quantyl\XML\Html\A("/Blog/Category?id={$this->_category->id}&page=$i", $i) . "</li>" ;
        }
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getBlogSummary() {
        $res = "" ;
        $posts = \Model\Blog\Post::LastFromCategory(
                    $this->_perpage,
                    $this->_category,
                    $this->_page * $this->_perpage
                );
        foreach ($posts as $p) {
            $res .= new \Widget\Blog\PostSummary($p) ;
        }
        return new \Answer\Widget\Misc\Section(\I18n::BLOG_POSTS(), "", $this->getPageSelector(), $res, "card-2-3 left") ;
    }
    
    public function getTplContent() {
        $res = "" ;
        
        $res .= $this->getBlogSummary() ;
        $res .= $this->getBlogMenu() ;
        
        return $res ;
    }
    
}
