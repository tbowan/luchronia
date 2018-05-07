
#------------------------------------------------------------------------------
# ajout des champs aux personnages
#------------------------------------------------------------------------------

ALTER TABLE `game_character`
    ADD `time`          int(11) NOT NULL,
    ADD `experience`    int(11) NOT NULL,
    ADD `level`         int(11) NOT NULL,
    ADD `point`         int(11) NOT NULL,
    ADD `sex`           int(11) NOT NULL,
    ADD `race`          int(11) NOT NULL,
    ADD `energy`        double  NOT NULL,
    ADD `hydration`     double  NOT NULL,
    ADD `last_update`   int(11) NOT NULL,
    ADD `credits`       double  not null,
    ADD `inventory`     int(11) not null,
    ADD `health`        double  not null,
    ADD `position`      int(11)     NULL,
    ADD `citizenship`   int(11)     null,
    ADD `nationality`   int(11)     null,
    ADD FOREIGN KEY (`position`)
        REFERENCES `game_city` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    ADD FOREIGN KEY (`citizenship`)
        REFERENCES `game_city` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    ADD FOREIGN KEY (`nationality`)
        REFERENCES `game_city` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE ;

#------------------------------------------------------------------------------
# Characteristic : base table
#------------------------------------------------------------------------------

CREATE TABLE `game_characteristic` (
  `id`   int(11)     NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Characteristic : value for characters
#------------------------------------------------------------------------------

CREATE TABLE `game_characteristic_character` (
  `id`             int(11)    NOT NULL AUTO_INCREMENT,
  `characteristic` int(11)    NOT NULL,
  `character`      int(11)    NOT NULL,
  `value`          int(11)    NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`characteristic`) REFERENCES `game_characteristic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`)      REFERENCES `game_character`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;