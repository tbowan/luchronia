<?php

namespace Answer\Widget\Game\Ressource ;

class Parcel extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SENDER(),
            \I18n::DESTINATION(),
            \I18n::DELIVERY(),
            \I18n::DETAILS()
        )) ;
        
        foreach (\Model\Game\Post\Parcel::GetFromRecipient($me) as $parcel) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Character/Show?id={$parcel->sender->id}", $parcel->sender->getName()),
                new \Quantyl\XML\Html\A("/Game/City?id={$parcel->destination->id}", $parcel->destination->getName()),
                ($parcel->tf < time()
                        ? \I18n::PARCEL_ARRIVED()
                        : \I18n::_date_time($parcel->tf - DT)
                        ),
                new \Quantyl\XML\Html\A("/Game/Post/Parcel?parcel={$parcel->id}", \I18n::DETAILS()),
            )) ;
        }
        
        if ($table->getRowsCount() > 0) {
            $res = "$table" ;
        } else {
            $res = \I18n::YOU_HAVENT_PARCELS() ;
        }
        
        parent::__construct(\I18n::MY_PARCELS(), "", "", $res, $classes) ;
        
    }
    
        
    
}
