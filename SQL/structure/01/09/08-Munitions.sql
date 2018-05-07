#------------------------------------------------------------------------------
# Munitions pour armes
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_munition` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `weapon`    int(11)     NOT NULL,
    `munition`  int(11)     NOT NULL,
    `amount`    int(11)     NOT NULL,
    `coef`      int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`weapon`)      REFERENCES `game_ressource_item`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`munition`)    REFERENCES `game_ressource_item`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
