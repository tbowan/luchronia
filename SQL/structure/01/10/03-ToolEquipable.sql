
SET @hand = (SELECT id from game_ressource_slot where name = "Hand") ;


#------------------------------------------------------------------------------
# Ajout des outils en tant qu'équipables
#------------------------------------------------------------------------------

INSERT INTO game_ressource_equipable
    (`item`, `slot`, `amount`, `race`, `sex`, `discretion`, `defense`, `resistance`, `impact`)
SELECT
    id, @Hand, 2, 0, 0, 0, 1, 0, 0
FROM
    game_ressource_item
WHERE
    name like "Tool%" ;

    