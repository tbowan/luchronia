#------------------------------------------------------------------------------
# Ajout des colonnes pour l'équivalence et la dernière modification
#------------------------------------------------------------------------------

ALTER TABLE wiki_page
    ADD equiv   int(11)         NULL,
    ADD last_update int(11) NOT NULL,
    ADD FOREIGN KEY (`equiv`)
        REFERENCES `wiki_page` (`id`)
        ON DELETE restrict ON UPDATE CASCADE ;

#------------------------------------------------------------------------------
# Ajout des valeurs par défaut
#------------------------------------------------------------------------------

update wiki_page set
    equiv       = id,
    last_update = unix_timestamp() ;




