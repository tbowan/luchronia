
#------------------------------------------------------------------------------
# Les questions qui sont déjà traitées par le système
#------------------------------------------------------------------------------

ALTER TABLE game_politic_question
    ADD `processed` tinyint(1) NOT NULL ;
