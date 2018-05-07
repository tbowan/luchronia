
#------------------------------------------------------------------------------
# Table des taxes commerciales
#------------------------------------------------------------------------------

CREATE TABLE `game_tax_tradable` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `instance`  int(11) NOT NULL,
    `order` double  NOT NULL,
    `trans` double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Suppression des tables spécifiques aux bâtiments commerciaux
#------------------------------------------------------------------------------

drop table
    game_building_academy,
    game_building_market ;

#------------------------------------------------------------------------------
# Table des bâtiments autorisant la prestation de compétences
#------------------------------------------------------------------------------

ALTER TABLE game_building_job
    ADD tradable tinyint(1) NOT NULL ;

update game_building_job
    set tradable = false ;

update game_building_job
    set tradable = true
    where `name`in ("Hospital", "Academy") ;

#------------------------------------------------------------------------------
# Table des compétences proposées en prestation
#------------------------------------------------------------------------------

CREATE TABLE `game_trading_skill` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `character` int(11) NOT NULL,
    `instance`  int(11) NOT NULL,
    `skill`     int(11) NOT NULL,

    `total`     int(11) NOT NULL,
    `remain`    int(11) NOT NULL,

    `price`     double  NOT NULL,
    `time`      double  NOT NULL,
    `use`      double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)          ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`)       REFERENCES `game_skill_skill` (`id`)        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

