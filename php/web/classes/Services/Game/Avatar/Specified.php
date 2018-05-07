<?php

namespace Services\Game\Avatar ;

class Specified extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        foreach (\Model\Game\Avatar\Layer::GetAll() as $l) {
            $form->addInput("layer-" . $l->getId(), new \Quantyl\Form\Model\Id(\Model\Game\Avatar\Item::getBddTable(), "", false)) ;
        }
    }
    
    public function getView() {
        
        $svg = new \Quantyl\XML\SVG\SVG(400, 640);
        foreach (\Model\Game\Avatar\Layer::GetAll() as $l) {
            $attrname = "layer-" . $l->getId() ;
            $item = $this->$attrname ;
            if ($item != null) {
                $url = "/Media/icones/Model/Avatar/" . $item->filename ;
                $svg->addChild(new \Quantyl\XML\SVG\Image($url, 0, 0, 400, 640)) ;
            }
        }
        return $svg ;
    }
    
    
}
