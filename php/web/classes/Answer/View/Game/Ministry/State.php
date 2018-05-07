<?php

namespace Answer\View\Game\Ministry ;

class State extends Base {

    public function getPrefectures($classes = "") {
        
        $res = "" ;
        
        if ($this->_city->prefecture !== null) {
            $res .= \I18n::STATE_CURRENT_PREFECTURE(
                    new \Quantyl\XML\Html\A(
                            "/Game/City?id={$this->_city->prefecture->id}",
                            $this->_city->prefecture->getName())
                    ) ;
        } else {
            $res .= \I18n::STATE_CURRENT_PREFECTURE_NONE() ;
        }
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CITY(),
            \I18n::DISTANCE(),
            \I18n::ACTIONS()
                )) ;
        
        foreach (\Model\Game\City\Prefecture::GetForCity($this->_city) as $pref) {
            $city = $pref->prefecture->instance->city ;
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", $city->getName()),
                $pref->distance,
                ($this->_isadmin
                        ? new \Quantyl\XML\Html\A(
                                "/Game/Ministry/State/ChangePrefecture?city={$this->_city->id}&prefecture={$pref->id}",
                                \I18n::STATE_CHOSE_PREFECTURE())
                        : "")
            )) ;
        }
        
        $res .= $table ;
        
        return new \Answer\Widget\Misc\Section(\I18n::STATE_PREFECTURES(), "", "", $res, $classes) ;
    }
    
    public function getSpecific() {
        
        return ""
                . $this->getPrefectures() ;
    }

}
