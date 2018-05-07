
#------------------------------------------------------------------------------
# Table des ephemerides pour le soleil
#------------------------------------------------------------------------------

CREATE TABLE `game_ephemeris_sun` (
    `id`    int(11)     NOT NULL AUTO_INCREMENT,
    `time`  int(11)     NOT NULL,
    `x`     double      NOT NULL,
    `y`     double      NOT NULL,
    `z`     double      NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

