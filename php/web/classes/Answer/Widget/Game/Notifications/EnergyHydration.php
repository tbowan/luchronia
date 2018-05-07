<?php

namespace Answer\Widget\Game\Notifications;

class EnergyHydration extends \Quantyl\Answer\Widget {

    private $_character;

    public function __construct(\Model\Game\Character $c) {
        parent::__construct();
        $this->_character = $c;
    }

    private function getEnergyHydration() {
        return \Model\Game\Ressource\Inventory::TotalEnergyHydration($this->_character) ;
    }

    public function getFeed($energy, $hydration) {
        return new Base(
                \I18n::ENERGY_AND_HYDRATATION(),
                \I18n::HINT_ENERGY_HYDRATION_LOW($energy, $hydration),
                "warning");
    }

    public function getRefuel($nh, $ne) {
        return new Base(
                \I18n::EH_INVENTORY_EMPTY_TITLE(),
                \I18n::EH_INVENTORY_EMPTY_MESSAGE(max(0, $nh), max(0, $ne)),
                "critical");
    }

    public function getContent() {
        $res = "";

        list($i_energy, $i_hydration) = $this->getEnergyHydration();

        $c_energy = $this->_character->getEnergy();
        $c_hydration = $this->_character->getHydration();

        $n_energy = ($i_energy + $c_energy - 750) / 720.0;
        $n_hydration = ($i_hydration + $c_hydration - 750) / 720.0;
        $n = min($n_energy, $n_hydration);

        if ($c_energy < 750 || $c_hydration < 750) {
            $res .= $this->getFeed($c_energy, $c_hydration);
            if ($n < 0) {
                $res .= $this->getRefuel($n_energy, $n_hydration);
            }
        }

        return $res;
    }

}
