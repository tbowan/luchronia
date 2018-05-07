<?php

namespace Answer\Widget\Game\Character ;

class OwnCard extends IdentityCard{
    public function getLink() {
        return new \Quantyl\XML\Html\A("/Game/Character/Sheet", \I18n::DETAILED_CHARACTER());
    }
}
