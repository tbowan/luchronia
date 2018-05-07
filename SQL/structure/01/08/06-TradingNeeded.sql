#------------------------------------------------------------------------------
# Prix des ressources pour la fourniture dans les chantiers
#------------------------------------------------------------------------------

CREATE TABLE `game_trading_needed` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `city`      int(11)     NOT NULL,
    `item`      int(11)     NOT NULL,
    `price`     double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)  REFERENCES `game_city`           (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
