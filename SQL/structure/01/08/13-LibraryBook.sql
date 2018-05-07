#------------------------------------------------------------------------------
# I. Table des éléments disponibles dans la bibliothèques
#------------------------------------------------------------------------------

CREATE TABLE `game_building_library` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `instance`  int(11)     NOT NULL,
    `name`      varchar(32) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)  REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

ALTER TABLE game_ressource_city
    DROP COLUMN `inside`,
    ADD `instance`  int(11)         NULL,
    ADD `price`     double          NULL,
    ADD `published` tinyint(1)  NOT NULL,
    ADD FOREIGN KEY (`instance`)  REFERENCES `game_building_instance` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ;

