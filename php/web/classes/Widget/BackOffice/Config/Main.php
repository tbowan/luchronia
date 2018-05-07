<?php

namespace Widget\BackOffice\Config ;

class Main extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::CONFIG_LIST() . "</h2>" ;
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::KEY_NAME(),
            \I18n::KEY_VALUE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Quantyl\Config::GetAll() as $cfg) {
            $table->addRow(array(
                "<strong>" . $cfg->key . "</strong><br/>" . \I18n::translate("CONFIG_" . $cfg->key),
                $cfg->value,
                new \Quantyl\XML\Html\A("/BackOffice/Config/Change?config={$cfg->id}", \I18n::EDIT())
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
        
    }
    
}
