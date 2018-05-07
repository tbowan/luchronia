<?php

namespace Model\Game\Politic ;

interface PoliticalSystem {
    
    public function countVoter() ;
    
    public function canManage(\Model\Game\Character $c) ;
    
    /* Change of Poliotical System */
    
    public function tryChange(System $target) ;
    
    public function doChange(System $target) ;
    
    public function doStart() ;
    
    /* Government */
    
    public function createGovernmentProject(\Model\Game\Character $author) ;
    
    public function proceedGovernmentProject(Government $gov) ;
    
    public static function GetFromSystem(System $base) ;
    
}
