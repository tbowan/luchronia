<?php

namespace Model\Blog  ;

class Category extends \Quantyl\Dao\BddObject {

    use \Model\Name ;
    use \Model\Illustrable ;
    
    public function getPosts($all = false) {
        return Post::GetFromCategory($this, $all) ;
    }

    public function canAccess($user) {
        $group = $this->group ;
        return \Model\Identity\Role::acl($user, $group) ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "lang" : return \Model\I18n\Lang::GetById($value) ;
            case "group" : return \Model\Identity\Group::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "lang" :
            case "group" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getNameField() {
        return "name" ;
    }

    public function getImgPath() {
        return $this->image ;
    }
    
    public static function GetFromLang(\Model\I18n\Lang $l) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `lang` = :lid",
                array("lid" => $l->id)
                ) ;
    }

}