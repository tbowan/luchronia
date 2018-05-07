<?php

namespace Model\Game\Social ;

class Post extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "author" :
                return \Model\Game\Character::GetById($value) ;
            case "access" :
                return \Model\Enums\Access::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "author" :
            case "access" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromAuthor(\Model\Game\Character $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `author` = :c"
                . " order by `date` desc",
                array ("c" => $c->getId())
                ) ;
    }
    
    public static function newPost(\Model\Game\Character $character, $content, \Model\Enums\Access $access) {
        $post = new Post() ;
        $post->author  = $character ;
        $post->date    = time() ;
        $post->content = $content ;
        $post->access  = $access ;
        $post->create() ;
        return $post ;
    }
    
    public static function getNews(\Model\Game\Character $character) {
        return static::getResult(""
                . "select * "
                . "from ("
                . "    select gsp.*"
                . "    from `" . self::getTableName() . "` as gsp"
                . "    inner join `game_social_follower` as gsf on gsp.author = gsf.b"
                . "    where gsf.a = :id"
                . "  union"
                . "    select gsp.*"
                . "    from `" . self::getTableName() . "` as gsp"
                . "    where gsp.author = :id"
                . ") as temp "
                . "order by `date` desc "
                . "limit 10",
                array ("id" => $character->getId())
                ) ;
    }
    
}
