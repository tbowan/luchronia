<?php

namespace Model\Blog  ;

class Post extends \Quantyl\Dao\BddObject {

    use \Model\Name ;

    public function getImage($class = null) {
        if ($this->image != "") {
            return new \Quantyl\XML\Html\Img(
                $this->image,
                $this->title,
                $class
                ) ;
        } else {
            return "" ;
        }
        
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "category" :
                return Category::createFromId($value) ;
            case "author" :
                return \Model\Game\Character::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "category" :
            case "author" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getNameField() {
        return "title" ;
    }
    
    // Get some sets
    
    public static function CountLastForLang(\Model\I18n\Lang $lang) {
        $query = "select count(p.id) as c"
                . " from `" . static::getTableName() . "` as p"
                . " join (select id from blog_category where lang = :lid) as c"
                . " on p.category = c.id"
                . " where"
                . " published and"
                . " `date` < :t"
                 ;
        
        return static::getCount($query, array("lid" => $lang->id, "t" => time())) ;
    }
    
    public static function GetLasts(\Model\I18n\Lang $lang, $number, $first = 0) {
        
        $query = "select p.*"
                . " from `" . static::getTableName() . "` as p"
                . " join (select id from blog_category where lang = :lid) as c"
                . " on p.category = c.id"
                . " where"
                . " published and"
                . " `date` < :t"
                . " order by `date` desc"
                . " limit $first, $number" ;
        return static::getResult($query, array("lid" => $lang->id, "t" => time())) ;
    }
    
    
    
    public static function CountLastFromCategory(Category $category) {
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where"
                . "     published and"
                . "     category = :cid and"
                . "     `date` < :t" ;
        
        return static::getCount($query, array("cid" => $category->getId(), "t" => time())) ;
    }
    
    public static function LastFromCategory($number, Category $category, $first = 0) {
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . "     published and"
                . "     category = :cid and"
                . "     `date` < :t"
                . " order by `date` desc"
                . " limit $first, $number" ;
        
        return static::getResult($query, array("cid" => $category->getId(), "t" => time())) ;
    }
    
    public static function GetFromCategory(Category $category, $all = false) {
        if (! $all) {
            $where = "and published and `date` < :t " ;
            $params = array("t" => time(), "id" => $category->getId()) ;
        } else {
            $where = "" ;
            $params = array("id" => $category->getId()) ;
        }
        
        $query = "select id"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " `category` = :id"
                . " $where"
                . " order by `date` desc ";
        
        return static::getResult($query, $params) ;
    }
    
    public static function FromAuthor(\Model\Game\Character $author) {
        $query = "select id"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " `author` = :author"
                . " order by `date` desc ";
        
        return static::getResult($query, array("author" => $author->getId())) ;
    }
    
    // Misc
    
    public static function CountAll(){
        
        $row = static::getSingleRow(
                "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where"
                . " published and"
                . " `date`< now()",
                array()
                ) ;

        return $row["c"] ;
    }
    
    public static function GetNewPublications() {
        return self::getResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where"
                . "     published and"
                . "     `date` < :time and"
                . "     isnull(`notified`)",
                array("time" => time())) ;
    }
    
}