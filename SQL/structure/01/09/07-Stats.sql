
#------------------------------------------------------------------------------
# Points de vie des bâtiments
#------------------------------------------------------------------------------

CREATE TABLE `stats_game_building_health` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `when`      int(11)     NOT NULL,
    `instance`  int(11)     NOT NULL,
    `job`       int(11)     NOT NULL,
    `level`     int(11)     NOT NULL,
    `health`    double      NOT NULL,
    `barricade` double      NOT NULL,
    `sun`       double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)   REFERENCES `game_building_instance`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Nombres de monstres
#------------------------------------------------------------------------------

CREATE TABLE `stats_game_city_monsters` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `when`          int(11)     NOT NULL,
    `city`          int(11)     NOT NULL,
    `monster_in`    double      NOT NULL,
    `monster_out`   double      NOT NULL,
    `sun`           double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)   REFERENCES `game_city`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
