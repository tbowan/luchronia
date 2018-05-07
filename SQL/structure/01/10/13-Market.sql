

#------------------------------------------------------------------------------
# Table des ordres de vente
#------------------------------------------------------------------------------

CREATE TABLE `game_trading_character_market_sell` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `character` int(11) NOT NULL,
    `market`    int(11) NOT NULL,
    `ressource` int(11) NOT NULL,
    `total`     int(11) NOT NULL,
    `remain`    int(11) NOT NULL,
    `price`     double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)          ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`market`)      REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`ressource`)   REFERENCES `game_ressource_item` (`id`)     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des ordres des taxes des marchés
#------------------------------------------------------------------------------

CREATE TABLE `game_building_market` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `instance`  int(11) NOT NULL,
    `tax_order` double  NOT NULL,
    `tax_trans` double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
