<?php

namespace Answer\Widget\Blog;

class Post extends \Answer\Widget\Misc\Section {

    public function __construct(\Model\Blog\Post $post, $classes = null) {

        $res = "" ;
        $img = $post->getImage() ;
        if ($img != "") {
            $res .= "<div class=\"blog-illustr\"> " ; 
            $res .= "$img";
            $res .= "</div>" ;
        }
        $res .= $post->head;
        $res .= $post->content;

        parent::__construct(
                $post->title,
                "",
                \I18n::FROM_WHEN(
                        $post->author->getName(),
                        \I18n::_date_time($post->date - DT)),
                $res, "card-2-3 $classes");
    }

}
