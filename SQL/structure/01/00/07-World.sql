
#------------------------------------------------------------------------------
# World
#------------------------------------------------------------------------------


CREATE TABLE `game_world` (
  `id`      int(11)     NOT NULL AUTO_INCREMENT,
  `name`    varchar(32) NOT NULL,
  `size`    int(11)     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Biome
#------------------------------------------------------------------------------

CREATE TABLE `game_biome` (
  `id`          int(11)     NOT NULL AUTO_INCREMENT,
  `name`        varchar(32) NOT NULL,
  `tmin`        double      NOT NULL,
  `tmax`        double      NOT NULL,
  `kmin`        double      NOT NULL,
  `kmax`        double      NOT NULL,
  `day_color`   varchar(7)  NOT NULL,
  `night_color` varchar(7)  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

INSERT INTO game_biome (`name`, tmin, tmax, kmin, kmax, day_color, night_color)
VALUES
  ("desert_warm",     0, 2,   0,  79, "#fdfbc9", "#e4e1e5"),
  ("desert_cold",    -2, 0,   0,  79, "#edf9f9", "#f6f5f7"),
  ("toundra_warm",    0, 2,  79, 103, "#e8f9b0", "#d0d7dc"),
  ("toundra_cold",   -2, 2,  79, 103, "#e8f9e6", "#eaedf4"),
  ("grassland_warm",  0, 2, 103, 127, "#d5f977", "#bedace"),
  ("grassland_cold", -2, 2, 103, 127, "#d5f9c1", "#e6f4f3"),
  ("bush_warm",       0, 2, 127, 154, "#d2e055", "#a1d1ca"),
  ("bush_cold",      -2, 2, 127, 154, "#e0e8b5", "#b2ced0"),
  ("forest_warm",     0, 2, 154, 256, "#76a21c", "#236773"),
  ("forest_cold",    -2, 2, 154, 256, "#76a26a", "#5e7f92") ;

#------------------------------------------------------------------------------
# City
#------------------------------------------------------------------------------

CREATE TABLE `game_city` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `name`          varchar(32) NOT NULL,
# - coordonnee normalisee dans la geode
    `world`         int(11)     NOT NULL,
    `coord`         varchar(20) NOT NULL,
# - Coordonnees cartesiennes
    `x`             double      NOT NULL,
    `y`             double      NOT NULL,
    `z`             double      NOT NULL,
# - coordonnees spheriques
    `latitude`      double      NOT NULL,
    `longitude`     double      NOT NULL,
# - parametre geographiques
    `altitude`      double      NOT NULL,
    `albedo`        double      NOT NULL,
    `biome`         int(11)         NULL,
# - parametres ensoleillement et mise à jour
    `sun`           double      NOT NULL,
    `last_update`   int(11)     NOT NULL,
# - richesses de la ville (stockes et crédits)
    `stocks`        int(11)     NOT null,
    `credits`       double      NOT NULL,
# - citoyennete et compagnie
    `citizenship`   int(11)     not null,
    `prefecture`    int(11)         null,
    `palace`        int(11)         null,
# - clés
    PRIMARY KEY (`id`),
    KEY (`coord`),
    FOREIGN KEY (`world`)
        REFERENCES `game_world` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`biome`)
        REFERENCES `game_biome` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`prefecture`)
        REFERENCES `game_city` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (`palace`)
        REFERENCES `game_city` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Voisinage
#------------------------------------------------------------------------------

CREATE TABLE `game_city_neighbour` (
  `id`    int(11) NOT NULL AUTO_INCREMENT,
  `a`     int(11) NOT NULL,
  `b`     int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`a`) REFERENCES `game_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`b`) REFERENCES `game_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;