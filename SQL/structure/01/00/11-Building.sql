#------------------------------------------------------------------------------
# Building : Base classes : job and type
#------------------------------------------------------------------------------

CREATE TABLE `game_building_job` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `name`          varchar(32) NOT NULL,
    `ministry`      int(11)         NULL,
    `technology`    int(11)     NOT NULL,
    `health`        int(11)     NOT NULL,
    `level`         int(11)     NOT null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ministry`)
        REFERENCES `game_politic_ministry` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_building_type` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `name`       varchar(32) NOT NULL,
  `wear`       double      NOT NULL,
  `technology` double      NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Building : Ressources to build (map, construction, complement)
#------------------------------------------------------------------------------

CREATE TABLE `game_building_complement` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `item`     int(11)    NOT NULL,
  `job`      int(11)    NOT NULL,
  `amount`   double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`) REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`job`)  REFERENCES `game_building_job`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_building_construction` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `item`     int(11)    NOT NULL,
  `type`     int(11)    NOT NULL,
  `amount`   double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item`)     REFERENCES `game_ressource_item`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`type`)     REFERENCES `game_building_type`     (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Building : instances in cities (instance, ruins, sites and needs)
#------------------------------------------------------------------------------

CREATE TABLE `game_building_instance` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `job`      int(11)    NOT NULL,
  `type`     int(11)    NOT NULL,
  `city`     int(11)    NOT NULL,
  `level`    int(11)    NOT NULL,
  `health`   double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`job`)  REFERENCES `game_building_job`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`type`) REFERENCES `game_building_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`city`) REFERENCES `game_city`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_building_ruin` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `instance` int(11)    NOT NULL,
  `job`      int(11)    NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`instance`) REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`job`)      REFERENCES `game_building_job`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_building_lost` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `job`      int(11)    NOT NULL,
  `type`     int(11)    NOT NULL,
  `city`     int(11)    NOT NULL,
  `level`    int(11)    NOT NULL,
  `health`   double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`job`)  REFERENCES `game_building_job`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`type`) REFERENCES `game_building_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`city`) REFERENCES `game_city`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_building_site` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `instance`    int(11) NOT NULL,
  `job`         int(11) NOT NULL,
  `last_update` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`instance`) REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`job`)      REFERENCES `game_building_job`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_building_need` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `site`     int(11)    NOT NULL,
  `item`     int(11)    NOT NULL,
  `needed`   double     NOT NULL,
  `provided` double     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`site`) REFERENCES `game_building_site`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`item`) REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Cout pour utiliser
#------------------------------------------------------------------------------

CREATE TABLE `game_building_cost` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `city`       int(11)     NOT NULL,
  `job`        int(11)     NOT NULL,
  `citizen`    double      NOT NULL,
  `stranger`   double      NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city`) REFERENCES `game_city`         (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`job`)  REFERENCES `game_building_job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;