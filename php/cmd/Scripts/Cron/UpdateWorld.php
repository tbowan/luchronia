<?php

namespace Scripts\Cron ;

class UpdateWorld extends \Scripts\Base {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("n", new \Quantyl\Form\Fields\Integer()) ;
        $form->addInput("k", new \Quantyl\Form\Fields\Integer()) ;
        $form->addInput("world", new \Quantyl\Form\Model\Id(\Model\Game\World::getBddTable(), "", false)) ;
    }
    
    public function doStuff() {
         if ($this->world == null) {
            foreach (\Model\Game\World::GetAll() as $world) {
                $this->updateWorld($world) ;
            }
        } else {
            $this->updateWorld($this->world) ;
        }
    }

    public function updatepercent($cnt) {
        $this->setPercent($cnt->getPercent()) ;
    }
    
    public function updateWorld(\Model\Game\World $w) {
        echo "Updating world \"{$w->name}\"\n" ;
        $cnt = new \Quantyl\Misc\Counter($w->getCityCount() / $this->n, 1, $this, "updatepercent") ;
        
        echo " - foreach cities\n" ;
        foreach (\Model\Game\City::GetFromWorld($w, $this->n, $this->k) as $c) {
            $c->read(true) ;
            $this->update($c) ;
            $c->update() ;
            $cnt->step() ;
        }
        
        $cnt->elapsed() ;
        echo " - done\n" ;
    }
    
    public function update_city_sun(\Model\Game\City &$c, $time) {
        $c->last_update = $time ;
        
        $sunpos = \Model\Game\Ephemeris\Sun::GetPosByTime($time) ;
        $citypos = $c->getCoordinate() ;
        $c->sun = $sunpos->ScalarProduct($citypos) ;
        
        if ($c->sunrise == 0 || $c->sunset == 0) {
            $c->sunrise = \Model\Game\Ephemeris\Sun::GetSunRise($citypos, $time) ;
            $c->sunset = \Model\Game\Ephemeris\Sun::GetSunSet($citypos, $time) ;
        } else if ($time > $c->sunrise) {
            $c->sunrise = \Model\Game\Ephemeris\Sun::GetSunRise($citypos, $c->sunset) ;
        } else if ($time > $c->sunset) {
            $c->sunset = \Model\Game\Ephemeris\Sun::GetSunSet($citypos, $c->sunrise) ;
        }
    }
    
    public function getTrueFitness(\Model\Game\City $c) {
        if ($c->sun > 0) {
            return $c->fitness ;
        } else {
            return (10 * $c->albedo + $c->fitness) ;
        }
    }
    
    public function update_monsters_logistic(\Model\Game\City &$c, $dt) {
        
        // Step 1 : reproduction
        $K      = 10 * $c->albedo ;
        $K_in   = max(1, 2 * $K) ;
        $K_out  = max(1, $this->getTrueFitness($c)) ;
        
        $nat    = 0.02 ; // natalité mondiale sur terre : 0.016 par an
        $year   = 60*60*24*360 ;
        $A      = $nat * $dt / $year ; // ~ 0.00017
        $A_in   = $A ;
        $A_out  = max(0, $c->sun * $A_in) ;
        
        $c->monster_in  = max(0, $c->monster_in  * (1.0 + $A_in * (1.0 - $c->monster_in  / $K_in ) )) ;
        $c->monster_out = max(0, $c->monster_out * (1.0 + $A_out * (1.0 - $c->monster_out / $K_out) )) ;
        
        // Step 2 : Moves
        
        $total_in  = $c->monster_in - $K_in ;
        $total_out = $c->monster_out - $K_out ;
        
        $move = ($total_in - $total_out) * $dt / (60*60*24) ;
        
        if ($move < 0 && $move < - $c->monster_out) {
            $move = - $c->monster_out ;
        } else if ($move > 0 && $move > $c->monster_in) {
            $move = $c->monster_in ;
        }
        
        $c->monster_in  -= $move ;
        $c->monster_out += $move ;
    }

    public function update_monsters_flow(\Model\Game\City &$c, $dt) {
        $here = $c->monster_out - $this->getTrueFitness($c)  ;
        if ($c->monster_out == 0) {
            $fitness_coef = 0 ;
        } else {
            $fitness_coef = min(0.5, max(-0.5, $c->fitness / $c->monster_out)) ;
        }
        // Flows
        $send = array() ;
        
        $total = 0 ;
        foreach (\Model\Game\City\Neighbour::getFromA($c) as $n) {
            // Send monsters
            $there = $n->b->monster_out - $this->getTrueFitness($n->b);
            if ($here > $there) {
                $move = ($here - $there) * $dt / (60*60*24) ;
                $send[] = array ($n->b, $move) ;
                $total += $move ;
            }
        }

        // Check if more monsters move
        if ($total > $c->monster_out) {
            $f = $c->monster_out / $total ;
            foreach ($send as $t) {
                $send[] = array ($t[0], $t[1] * $f) ;
            }
            $total = $c->monster_out ;
        }
        
        // True movements
        foreach ($send as $t) {
            $b = $t[0] ;
            $b->monster_out += $t[1] ;
            $b->fitness += $t[1] * $fitness_coef ;
            $b->update() ;
        }
        
        $c->monster_out -= $total ;
        $c->fitness -= $total * $fitness_coef ;
        $c->fitness -= 0.2 * $c->fitness * $dt / (60*60*24) ;
        
    }
    
    public function update_monsters(\Model\Game\City &$c, $dt) {
        if ($dt != 0) {
            $this->update_monsters_logistic($c, $dt) ;
            $this->update_monsters_flow($c, $dt) ;
            
            $damages = $c->monster_out * 1 * $dt / (24*60*60) ;
            //$c->fitness = min(0, $c->fitness + (0.1 * $damages) + ($dt / (24*60*60))) ;
            
            return $damages ;
        } else {
            return 0 ;
        }
    }
    
    public function update(\Model\Game\City $c) {
        // $c->read(true) ;
        // Get some informations
        $time = time() ;
        $dt = $time - $c->last_update ;

        // 1. City
        $this->update_city_sun($c, $time) ;
        $dammages = $this->update_monsters($c, $dt) ;
        
        $fitness = 0 ;
        $prestige = 0 ;
        // update buildings
        foreach (\Model\Game\Building\Instance::GetForUpdate($c) as $instance) {
            $obj = $instance->getTrueObject() ;
            $dammages = $obj->attack($dammages) ;
            $obj->iterate($dt) ;
            $fitness  += $instance->getFitness()  * $dt / (24*60*60) ;
            $prestige += $instance->getPrestige() * $dt / (24*60*60) ;
        }

        $c->fitness  += $fitness ;
        $this->addPrestige($c, $prestige) ;
        
        $dt = $dt / (24*60*60) ;
        $dammages = $dammages / $dt ;
        // 2. character presents
        foreach (\Model\Game\Character::getForUpdate($c) as $char) {
            
            $dammages = $char->attack($dammages, $dt) ;
        }
        
        // 3. Fitness and prestige
        
        // 4. update to bdd
        $c->update() ;
        
        return ;
        
    }
    
    public function addPrestige(\Model\Game\City &$city, $prestige) {
        if ($prestige > 0) {
            $after_out = $this->PrestigeOut($city, $prestige) ;
            $after_in  = $this->PrestigeIn($city, $after_out) ;
            $city->prestige += $after_in ;
        }
    }
    
    public function PrestigeOut(\Model\Game\City $city, $prestige) {
        $taxes = $this->PrestigeOutTaxes($city) ;
        $given = $prestige * $taxes ;
        $remain = $this->PrestigeOutGive($city, $given) ;
        return $prestige - $given + $remain;
    }
    
    public function PrestigeOutTaxes(\Model\Game\City $city) {
        $taxes = 0 ;
        $cnt = 0 ;
        foreach (\Model\Game\Building\Instance::GetFromCityAndJob($city, \Model\Game\Building\Job::GetByName("Prefecture")) as $inst) {
            $pref = \Model\Game\Building\Prefecture::GetFromInstance($inst) ;
            $taxes += $pref->prestige_out ;
        }
        if ($cnt > 0) {
            $taxes /= $cnt ;
        }
        
        return $taxes ;
    }
    
    public function PrestigeOutGive(\Model\Game\City $city, $prestige) {
        
        $target = array() ;
        
        foreach (\Model\Game\City::GetFromPrefecture($city) as $potential) {
            if ($potential->hasTownHall() && ! $city->equals($potential)) {
                $target[] = $potential ;
            }
        }
        
        if (count($target) > 0) {
            $g = $prestige / count($target) ;
            foreach ($target as $c) {
                $c->prestige += $g ;
                $c->update() ;
            }
            return 0 ;
        } else {
            return $prestige ;
        }
    }
    
    public function PrestigeIn(\Model\Game\City $city, $prestige) {
        if ($city->hasTownHall()) {
            return $this->PrestigeInTH($city, $prestige) ;
        } else {
            return $this->PrestigeInGive($city, $prestige) ;
        }
    }
    
    public function PrestigeInTH(\Model\Game\City $city, $prestige) {
        if ($city->prefecture !== null && ! $city->equals($city->prefecture)) {
            $taxes = $this->PrestigeInTaxes($city) ;
            $given = $prestige * $taxes ;
            
            $city->prefecture->prestige += $given ;
            $city->prefecture->update() ;
            
            return $prestige - $given ;
        } else {
            return $prestige ;
        }
    }
    
    public function PrestigeInTaxes(\Model\Game\City $city) {
        $taxes = 0 ;
        $cnt = 0 ;
        foreach (\Model\Game\Building\Instance::GetFromCityAndJob($city->prefecture, \Model\Game\Building\Job::GetByName("Prefecture")) as $inst) {
            $pref = \Model\Game\Building\Prefecture::GetFromInstance($inst) ;
            $taxes += $pref->prestige_in ;
        }
        if ($cnt > 0) {
            return $taxes /= $cnt ;
        } else {
            return $taxes ;
        }
    }
    
    public function PrestigeInGive(\Model\Game\City $city, $prestige) {
        $prefs = array() ;
        foreach (\Model\Game\City\Prefecture::GetForCity($city) as $p) {
            if (! $city->equals($p->prefecture->instance->city)) {
                $prefs[] = $p->prefecture ;
            }
        }
        
        $remain = $prestige ;
        
        $d = count($prefs) ;
        foreach ($prefs as $p) {
            $given = $prestige * $p->prestige_in / $d ;
            $p->instance->city->prestige += $given ;
            $p->instance->city->update() ;
            $remain -= $given ;
        }
        
        return $remain ;
    }
}
