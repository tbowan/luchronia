
#------------------------------------------------------------------------------
# Ajout des colonnes pour le prestige
#------------------------------------------------------------------------------

ALTER TABLE game_city
    add prestige double not null ;

ALTER TABLE game_ressource_item
    add prestige double not null ;

ALTER TABLE game_building_job
    add prestige double not null,
    add fitness  double not null ;

ALTER TABLE game_building_type
    add prestige double not null,
    add fitness  double not null ;

#------------------------------------------------------------------------------
# Table et colonnes pour les livraisons
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_delivery` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `target`        int(11) NOT NULL,
    `scheduled`     int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

ALTER TABLE game_ressource_treasure
    add delivery int(11) null,
    add FOREIGN KEY (`delivery`)
        REFERENCES `game_ressource_delivery` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE ;

#------------------------------------------------------------------------------
# Valeures de prestige et fitness pour les bâtiments
#------------------------------------------------------------------------------

# Defaults values
update game_building_type set prestige = 1.0, fitness = 1.0 ;

update game_building_job  set prestige = technology / 100, fitness = 1.0 ;

update game_building_job set fitness = -10 where name = "Wall" ;

update game_ressource set prestige = 