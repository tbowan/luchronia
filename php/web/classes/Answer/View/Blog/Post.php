<?php

namespace Answer\View\Blog ;

class Post extends \Answer\Decorator\Blog {
    
    private $_post ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\Blog\Post $post) {
        parent::__construct($service, "");
        $this->_post = $post ;
    }
    
    public function getTplContent() {
        $res = new \Answer\Widget\Blog\Post($this->_post, "left") ;
        $res .= $this->getBlogMenu() ;
        return $res ;
    }
    
    public function getTplMeta() {
        $server  =  \Quantyl\Server\Server::getInstance() ;
        $hostname = $server->getServerHostname() ;
        
        $datas = array (
                "og:title" => $this->_post->title,
                "og:type"  => "article",
                "og:url"   => "http://{$hostname}/Blog/Post?id={$this->_post->id}",
                "og:image" => $this->_post->image,
                "og:description" => $this->_post->head,
                "og:site_name" => \I18n::LUCHRONIA(),
                "article:section" => $this->_post->category->getName()
            ) ;
            
        $res = "" ;
        foreach ($datas as $key => $value) {
            $res .= "<meta property=\"$key\" content=\"" . htmlspecialchars(strip_tags($value), ENT_QUOTES, "UTF-8") . "\" />\n" ;
        }
        
        return $res ;    
    }
}
