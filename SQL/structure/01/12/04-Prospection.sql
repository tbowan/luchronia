

#------------------------------------------------------------------------------
# Table des prospections
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_prospection` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `character` int(11) NOT NULL,
    `city`      int(11) NOT NULL,
    `item`      int(11) NOT NULL,
    `when`      int(11) NOT NULL,
    `coef`      double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)      ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`city`)        REFERENCES `game_city` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)        REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
