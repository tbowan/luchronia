#------------------------------------------------------------------------------
# Table des bonus
#------------------------------------------------------------------------------

CREATE TABLE `game_characteristic_equipable` (
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `equipable`         int(11) NOT NULL,
    `characteristic`    int(11) NOT NULL,
    `bonus`             double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`equipable`)      REFERENCES `game_ressource_equipable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`characteristic`) REFERENCES `game_characteristic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Remplissage de la table
#------------------------------------------------------------------------------

SET @Discretion = (SELECT id from game_characteristic where name = "Discretion") ;
SET @Defense    = (SELECT id from game_characteristic where name = "Defense") ;
SET @Resistance = (SELECT id from game_characteristic where name = "Resistance") ;
SET @Impact     = (SELECT id from game_characteristic where name = "Impact") ;

INSERT INTO game_characteristic_equipable
    (`equipable`, `characteristic`, `bonus`)
SELECT id, @Discretion, `discretion` from game_ressource_equipable where discretion <> 0 UNION
SELECT id, @Defense,    `defense`    from game_ressource_equipable where defense    <> 0 UNION
SELECT id, @Resistance, `resistance` from game_ressource_equipable where resistance <> 0 UNION
SELECT id, @Impact,     `impact`     from game_ressource_equipable where impact     <> 0
;

#------------------------------------------------------------------------------
# Suppression des colonnes inutiles
#------------------------------------------------------------------------------

ALTER TABLE game_ressource_equipable
    DROP COLUMN discretion,
    DROP COLUMN defense,
    DROP COLUMN resistance,
    DROP COLUMN impact ;

