<?php

namespace Widget\BackOffice\Ecom ;

class Allopass extends \Quantyl\Answer\Widget {
    
    
    public function getConfig() {
        $res = "<h2>" . \I18n::CONFIG() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::KEY_NAME(),
            \I18n::KEY_VALUE(),
            \I18n::ACTIONS()
        )) ;
        
        $keys = array("ALLOPASS_SITE_ID") ;
        foreach ($keys as $k) {
            $cfg = \Model\Quantyl\Config::GetFromKey($k) ;
            $table->addRow(array(
                "<strong>" . $cfg->key . "</strong><br/>" . \I18n::translate("CONFIG_" . $cfg->key),
                $cfg->value,
                new \Quantyl\XML\Html\A("/BackOffice/Config/Change?config={$cfg->id}", \I18n::EDIT())
            )) ;
        }
        $res .= $table ;
        return $res ;
    }
    
    public function getStats() {
        $res = "<h2>" . \I18n::STATS() . "</h2>" ;
        
        
        return $res ;
    }
    
    public function getProducts() {
        $res = "<h2>" . \I18n::ALLOPASS_PRODUCTS() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::NAME(),
            \I18n::IDD(),
            \I18n::AMOUNT(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Ecom\Allopass\Product::GetAll() as $ap) {
            $table->addRow(array(
                $ap->name,
                $ap->idd,
                $ap->amount,
                new \Quantyl\XML\Html\A("/BackOffice/Ecom/Allopass/Edit?product={$ap->id}", \I18n::EDIT())
                . new \Quantyl\XML\Html\A("/BackOffice/Ecom/Allopass/Delete?product={$ap->id}", \I18n::DELETE())
            )) ;
        }
        $res .= $table ;
        
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Ecom/Allopass/Add", \I18n::ADD()) ;
        
        return $res ;
        
    }
    
    public function getContent() {
        return ""
                . $this->getConfig()
                . $this->getStats()
                . $this->getProducts() ;
    }
    
}
