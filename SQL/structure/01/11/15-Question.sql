
#------------------------------------------------------------------------------
# Ajout du nombre de points de vote pour les questions
#------------------------------------------------------------------------------

ALTER TABLE game_politic_question
    add `point` int(11) NOT NULL ;

#------------------------------------------------------------------------------
# Ajout du classement des candidats
#------------------------------------------------------------------------------

ALTER TABLE game_politic_candidate
    add `rank` int(11) NULL ;