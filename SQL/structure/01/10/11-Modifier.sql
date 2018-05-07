
#------------------------------------------------------------------------------
# Table des modifier
#------------------------------------------------------------------------------

CREATE TABLE `game_modifier` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `name`          varchar(32) NOT NULL,
    `time`          int(11) NOT NULL,
    `experience`    int(11) NOT NULL,
    `level`         int(11) NOT NULL,
    `energy`        int(11) NOT NULL,
    `hydration`     int(11) NOT NULL,
    `health`        int(11) NOT NULL,
    `position`      int(11)     NULL,
    `duration`      int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`position`)       REFERENCES `game_city` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des modifier pour les caractéristiques
#------------------------------------------------------------------------------

CREATE TABLE `game_characteristic_modifier` (
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `modifier`          int(11) NOT NULL,
    `characteristic`    int(11) NOT NULL,
    `bonus`             double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`modifier`)       REFERENCES `game_modifier` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`characteristic`) REFERENCES `game_characteristic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des modifier actifs
#------------------------------------------------------------------------------

CREATE TABLE `game_character_modifier` (
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `modifier`          int(11) NOT NULL,
    `character`         int(11) NOT NULL,
    `until`             int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`modifier`)  REFERENCES `game_modifier` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des objets qui confèrent des bonus
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_modifier` (
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `modifier`          int(11) NOT NULL,
    `item`              int(11) NOT NULL,
    `race`              int(11) NOT NULL,
    `sex`               int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`modifier`)  REFERENCES `game_modifier` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)      REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
