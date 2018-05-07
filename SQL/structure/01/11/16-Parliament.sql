

#------------------------------------------------------------------------------
# Le premier parlement n'as pas de question associée
#------------------------------------------------------------------------------

ALTER TABLE game_politic_parliament
    change `question` `question` int(11) NULL ;

