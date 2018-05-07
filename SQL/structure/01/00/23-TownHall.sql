
#------------------------------------------------------------------------------
# Table des hôtels de ville
#------------------------------------------------------------------------------

CREATE TABLE `game_building_townhall` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `instance`  int(11)     NOT NULL,
    `name`      varchar(32) NOT NULL,
    `welcome`   text        NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`) REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;


