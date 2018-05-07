
#------------------------------------------------------------------------------
# Ajout des colonnes pour les connexions
#------------------------------------------------------------------------------

ALTER TABLE game_character
    ADD `since`    INT(11) NOT NULL,
    ADD `previous` INT(11) NOT NULL,
    ADD `last`     INT(11) NOT NULL
    ;

#------------------------------------------------------------------------------
# Initialisation des "since"
#------------------------------------------------------------------------------

update game_character set since = unix_timestamp() ;
