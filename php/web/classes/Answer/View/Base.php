<?php

namespace Answer\View ;

use Quantyl\Answer\Template;

abstract class Base extends Template {
    
    private $_service ;
    
    public function __construct($service) {
        parent::__construct("style/template.php") ;
        $this->_service = $service ;
    }
    
    public function getService() {
        return $this->_service ;
    }
    
    /* Misc helpfull methods */
    
    public function isConnected() {
        return isset($_SESSION["char"]) ;
    }
    
    public function getUser() {
        return $_SESSION["user"] ;
    }
    
    public function getCharacter() {
        return $_SESSION["char"] ;
    }
    
    public function isAdmin() {
        try {
            $admin = new \Quantyl\ACL\Admin(\Quantyl\Server\Server::getInstance()->getConfig()) ;
            $admin->checkPermission() ;
            return true ;
        } catch (\Exception $e) {
            return false ;
        }
    }
    
    /* File Template functions */
    
    public function getTplTitle() {
        return $this->_service->getTitle() ;
    }
    
    public function getTplMeta() {
        return "" ;
    }
    
    public function getTplSession() {
        if ($this->isConnected()) {
            return new \Answer\Widget\Menu\Avatar($_SESSION["char"]) ;
        } else {
            return new \Answer\Widget\Menu\Play() ;
        }
    }
    
    public function getTplGameMenu() {
        $res = "" ;
        if (isset($_SESSION["char"])) {
            $res .= "<ul>";

            $items = array (
                "Main"          => "/Game",
                "Character"     => "/Game/Character",
                "Social"        => "/Game/Social",
                "Ressources"    => "/Game/Inventory",
                "Position"      => "/Game/City",
                "Account"       => "/User",
                "Logout"        => "/User/Logout"
            ) ;
            
            foreach ($items as $key => $url) {
                $res .= '<li><a href="' . $url . '">'
                    . '<img class="menu_icon" src="/Media/icones/misc/' . $key . '_light.png"/>'    
                    . '<span class="menu_text">'
                    . '<strong>' . \I18n::translate("MENU_GAME_" . strtoupper($key) . "_STRONG") . '</strong>'
                    . '<span>' . \I18n::translate("MENU_GAME_" . strtoupper($key) . "_SPAN") . '</span>'
                    . '</span>'
                    . '</a></li>';
            }

            $res .= "</ul>";
        } else {
            $res .= "<ul>";

            $items = array (
                "Register"      => "/User/Create",
            ) ;
            
            foreach ($items as $key => $url) {
                $res .= '<li><a href="' . $url . '">'
                    . \I18n::REGISTER_MENU_ICON()
                    . '<span class="menu_text">'
                    . '<strong>' . \I18n::translate("MENU_GAME_" . strtoupper($key) . "_STRONG") . '</strong>'
                    . '<span>' . \I18n::translate("MENU_GAME_" . strtoupper($key) . "_SPAN") . '</span>'
                    . '</span>'
                    . '</a></li>';
            }

            $res .= "</ul>";
        }
        return $res ;
    }
    
    public function getTplSiteMenu() {

        $res = "<ul>";

        $items = array (
            "Forum" => "/Forum",
            "Blog" => "/Blog",
            "Wiki" => "/Wiki"
        ) ;
        
        if ($this->isAdmin()) {
            $items["Backoffice"] = "/BackOffice";
        }

        foreach ($items as $key => $url) {
            $res .= '<li><a href="' . $url . '">'
                . '<img class="menu_icon" src="/Media/icones/misc/' . $key . '_light.png"/>'
                . '<span class="menu_text">'
                . '<strong>' . \I18n::translate("MENU_SITE_" . strtoupper($key) . "_STRONG") . '</strong>'
                . '<span>' . \I18n::translate("MENU_SITE_" . strtoupper($key) . "_SPAN") . '</span>'
                . '</span>'
                . '</a></li>';
        }

        $res .= "</ul>";
        
        return $res ;
    }

    public abstract function getTplContent() ;
    
    public function getTplFooter() {
        return \I18n::MAIN_FOOTER(VERSION);
    }
    
    
}