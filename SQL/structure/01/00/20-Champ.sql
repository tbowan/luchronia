#------------------------------------------------------------------------------
# Les champs
#------------------------------------------------------------------------------

CREATE TABLE `game_building_field` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `instance`  int(11)     NOT NULL,
    `item`      int(11)     NOT NULL,
    `amount`    double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)        REFERENCES `game_ressource_item`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Les compétences du champ
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_field` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `skill`     int(11)     NOT NULL,
    `item`      int(11)     NOT NULL,
    `gain`      int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`skill`)       REFERENCES `game_skill_skill`       (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)        REFERENCES `game_ressource_item`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`gain`)        REFERENCES `game_ressource_item`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
