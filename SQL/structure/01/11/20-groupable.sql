#------------------------------------------------------------------------------
# Suppression de la colone "groupable" qui ne sert plus
#------------------------------------------------------------------------------

ALTER TABLE game_ressource_item
    DROP COLUMN groupable ;