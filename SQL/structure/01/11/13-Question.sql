

#------------------------------------------------------------------------------
# Les questions qui sont déjà traitées par le système
#------------------------------------------------------------------------------

ALTER TABLE game_politic_question
    ADD `turnout` double NULL,
    ADD `answer`  double NULL ;