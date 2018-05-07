<?php

namespace Widget\Blog ;

use Model\Blog\Post;
use Quantyl\Answer\Answer;

class Rss extends Answer {
    
    private $_title ;
    private $_hostname ;
    private $_posts ;
    
    public function __construct($title, $hostname, $posts) {
        parent::__construct() ;
        $this->_title       = $title ;
        $this->_hostname    = $hostname ;
        $this->_posts       = $posts ;
    }
    
    public function addPost(Post $p) {
        $this->_posts[] = $p ;
    }
    
    public function getContent() {
        
        $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" ;
        $content .= "<rss version=\"2.0\">\n" ;
        $content .= "\t<channel>\n" ;
        $content .= "\t\t<title>" . $this->_title . "</title>\n" ;
        $content .= "\t\t<lastBuildDate>" . date("r") . "</lastBuildDate>\n" ;
        $content .= "\t\t<link>http://" . $this->_hostname . "</link>\n" ;

        
        foreach ($this->_posts as $p) {
            $content .= "\t\t<item>\n" ;
            $content .= "\t\t\t<title>{$p->title}</title>\n" ;
            $content .= "\t\t\t<description><![CDATA[" ;
            $content .= $p->getImage() ;
            $content .= $p->head ;
            $content .= "]]></description>\n" ;
            $content .= "\t\t\t<pubDate>" . date("r", $p->date) . "</pubDate>\n" ;
            $content .= "\t\t\t<link>http://" ;
            $content .= $this->_hostname ;
            $content .= "/Blog/Post?id={$p->id}" ;
            $content .= "</link>\n" ;
            $content .= "\t\t</item>\n" ;
        }
        
        $content .= "\t</channel>\n" ;
        $content .= "</rss>\n" ;
        
        return $content ;
    }

    public function sendHeaders(\Quantyl\Server\Server $srv) {
        $srv->header('Content-type: application/rss+xml') ;
    }

    public function isDecorable() {
        return false ;
    }

}
