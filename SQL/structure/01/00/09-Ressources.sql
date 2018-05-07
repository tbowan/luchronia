
#------------------------------------------------------------------------------
# Ressources : Item
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_item` (
  `id`           int(11)     NOT NULL AUTO_INCREMENT,
  `name`         varchar(32) NOT NULL,
  `groupable`    tinyint(1)  NOT NULL,
  `energy`       double          NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ressources : City (natural and stocks)
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_natural` (
  `id`   int(11)    NOT NULL AUTO_INCREMENT,
  `item` int(11)    NOT NULL,
  `city` int(11)    NOT NULL,
  `coef` double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`) REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`city`) REFERENCES `game_city`           (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_ressource_city` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `city`      int(11)     NOT NULL,
    `item`      int(11)     NOT NULL,
    `inside`    tinyint(1) NOT NULL,
    `amount`    double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`) REFERENCES `game_city`             (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`) REFERENCES `game_ressource_item`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ressources : character : slots, equipable, inventory, ...
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_slot` (
  `id`     int(11)     NOT NULL AUTO_INCREMENT,
  `name`   varchar(16) NOT NULL,
  `amount` int(11)     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `game_ressource_equipable` (
  `id`     int(11)    NOT NULL AUTO_INCREMENT,
  `item`   int(11)    NOT NULL,
  `slot`   int(11)    NOT NULL,
  `amount` double     NOT NULL,
  `race`   int(11)    NULL,
  `sex`   int(11)    NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`) REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`slot`) REFERENCES `game_ressource_slot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_ressource_inventory` (
  `id`        int(11)    NOT NULL AUTO_INCREMENT,
  `item`      int(11)    NOT NULL,
  `character` int(11)    NOT NULL,
  `slot`      int(11)    NULL,
  `amount`    double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`)      REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`slot`)      REFERENCES `game_ressource_slot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ressources : ecosystem
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_ecosystem` (
  `id`     int(11)    NOT NULL AUTO_INCREMENT,
  `item`   int(11)    NOT NULL,
  `biome`  int(11)    NOT NULL,
  `min`    double     NOT NULL,
  `max`    double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`biome`) REFERENCES `game_biome`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ressources : Energy (eat, vitalize, burn)
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_eatable` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `item`        int(11) NOT NULL,
  `energy`      double  NOT NULL,
  `race`        int(11)     NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_ressource_drinkable` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `item`        int(11) NOT NULL,
  `energy`      double  NOT NULL,
  `hydration`   double  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;