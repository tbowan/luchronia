#------------------------------------------------------------------------------
# I. Table des académies
#------------------------------------------------------------------------------

CREATE TABLE `game_building_academy` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `instance`  int(11)     NOT NULL,
    `name`      varchar(32) NOT NULL,
    `tax_order` double      NOT NULL,
    `tax_trans` double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)  REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

