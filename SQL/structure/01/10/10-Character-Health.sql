
#------------------------------------------------------------------------------
# points de vies multipliés par 100
#------------------------------------------------------------------------------

update game_character set health = 100 * (`level` + 1) ;
