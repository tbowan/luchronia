<?php

namespace Answer\View\Game\Building ;

use Model\Game\City;
use Quantyl\Answer\Widget;
use Quantyl\XML\Html\Img;

class FreeSlot extends \Answer\View\Base {
    
    private $_city ;
    private $_admin ;
    
    public function __construct($service, City $city, $admin) {
        parent::__construct($service) ;
        $this->_city  = $city ;
        $this->_admin = $admin ;
    }
    
    public function getTplContent() {
        
        return ""
                . '<div class="card-1-2">'
                . $this->getDescription()
                . $this->getInformation()
                . '</div>'
                . '<div class="card-1-2">'
                . $this->getSpecific()
                . '</div>'
                ;

    }
    
    public function getDescription() {
        
        $content = "" ;
        
        $img = new Img("/Media/icones/Model/Building/Free.png", \I18n::FREE_SLOT()) ;
        $img->setAttribute("class", "left card-1-3") ;
        
        $content .= $img ;
        $content .= \I18n::BUILDING_JOB_DESCRIPTION_Free() ;
        
        return new \Answer\Widget\Misc\Section(\I18n::DESCRIPTION(), "", "", $content, "") ;
    }
    
    public function getInformation() {
        
        $content  = "" ;
        $content .= "<ul>" ;
        $content .= "<li><strong>" . \I18n::CITY() . \I18n::HELP("/Wiki/") . " :</strong> " . $this->_city->getName() . " </li>" ;
        $content .= "</ul>" ;
        
        
        return new \Answer\Widget\Misc\Section(\I18n::INFORMATIONS(), "", "", $content, "") ;
    }
    
    public function getSpecific() {
        
        $content = "" ;
        if ($this->_admin) {
            $content .= \I18n::BUILDING_CAN_CREATE($this->_city->id) ;
        } else {
            $content .= \I18n::BUILDING_CANNOT_CREATE() ;
        }
        
        
        return new \Answer\Widget\Misc\Section(\I18n::FREE_SLOT(), "", "", $content, "") ;
    }
    
}
