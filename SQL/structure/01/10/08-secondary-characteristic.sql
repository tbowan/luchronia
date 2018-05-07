#------------------------------------------------------------------------------
# Colonne pour les primaires/secondaires
#------------------------------------------------------------------------------

ALTER TABLE game_characteristic
    ADD `primary` tinyint(1) NOT NULL ;

update game_characteristic set `primary` = 1 ;

#------------------------------------------------------------------------------
# Table pour les secondaires
#------------------------------------------------------------------------------

CREATE TABLE `game_characteristic_secondary` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `secondary` int(11)     NOT NULL,
    `base`      int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`secondary`) REFERENCES `game_characteristic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`base`)      REFERENCES `game_characteristic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ajout des characteristiques secondaires
#------------------------------------------------------------------------------

INSERT INTO game_characteristic
    (`name`, `primary`)
VALUES
    ("Discretion", 0),
    ("Defense", 0),
    ("Resistance", 0),
    ("Impact", 0) ;

#------------------------------------------------------------------------------
# Ajout des bases pour les secondaires
#------------------------------------------------------------------------------

insert into game_characteristic_secondary (`secondary`, `base`)
select `secondary`.id as `to`, `base`.id as `base`
from
    game_characteristic as `secondary`,
    game_characteristic as `base`
where
    (`secondary`.`name` = "Discretion" and `base`.`name` in ("Perception", "Mental"  )) OR
    (`secondary`.`name` = "Defense"    and `base`.`name` in ("Strength",   "Mental"  )) OR
    (`secondary`.`name` = "Resistance" and `base`.`name` in ("Perception", "Strength")) OR
    (`secondary`.`name` = "Impact"     and `base`.`name` in ("Strength",   "Charisma"))
 ;
