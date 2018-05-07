<?php

namespace Model\Game\Politic ;

class Anarchy implements PoliticalSystem {
    
    private $_system ;
    
    public function __construct(System $s) {
        $this->_system = $s ;
    }
    
    public function countVoter() {
        return 0 ;
    }
    
    public function canManage(\Model\Game\Character $c) {
        return false ;
    }

    public function doChange(System $target) {
        $target->start = time() ;
        $target->update() ;
    }

    public function doStart() {
        return ;
    }
    
    public function tryChange(System $target) {
        // TODO : better exception
        throw new \Exception() ;
    }

    public static function GetFromSystem(System $base) {
        return new Anarchy($base) ;
    }

    public function createGovernmentProject(\Model\Game\Character $author) {
        throw new \Exception() ;
    }

    public function proceedGovernmentProject(Government $gov) {
        throw new \Exception() ;
    }

}
