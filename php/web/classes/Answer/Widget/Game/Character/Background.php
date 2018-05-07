<?php

namespace Answer\Widget\Game\Character ;

class Background extends \Answer\Widget\Misc\Section  {
    
    public function __construct(\Model\Game\Character $c, $isadmin, $classes = "") {
        parent::__construct(
                \I18n::BACKGROUND(),
                ($isadmin ? new \Quantyl\XML\Html\A("/Game/Character/Background?c={$c->id}", \I18n::WRITE_BACKGROUND()) : ""),
                "",
                ($c->background == "" ? \I18n::NO_BACKGROUND() : $c->background),
                $classes
                );
    }
    
}
