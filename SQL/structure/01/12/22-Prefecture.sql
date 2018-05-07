#------------------------------------------------------------------------------
# Table des préfectures
#------------------------------------------------------------------------------

CREATE TABLE `game_building_prefecture` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `instance`      int(11) NOT NULL,
    `prestige_in`   int(11) NOT NULL,
    `prestige_out`  int(11) not null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)        REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des préfectures à portée
#------------------------------------------------------------------------------

CREATE TABLE `game_city_prefecture` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `city`          int(11) NOT NULL,
    `prefecture`    int(11) NOT NULL,
    `distance`      int(11) not null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)        REFERENCES `game_city` (`id`)                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`prefecture`)  REFERENCES `game_building_prefecture` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
