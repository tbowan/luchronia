
#------------------------------------------------------------------------------
# Ajout d'une colonne pour les personnages verrouillés
#------------------------------------------------------------------------------

alter table game_character
    add `locked` tinyint(1) NOT NULL ;
