
#------------------------------------------------------------------------------
# Table des mouvements
#------------------------------------------------------------------------------

CREATE TABLE `game_city_register` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `date`      int(11) NOT NULL,
    `character` int(11) NOT NULL,
    `city`      int(11)     NULL,
    `from`      int(11)     NULL,
    `to`        int(11)     NULL,
    `ressource` int(11)     NULL,
    `amount`    double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)          ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`city`)        REFERENCES `game_city` (`id`)               ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`from`)        REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`to`)          REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
