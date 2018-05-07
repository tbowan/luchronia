
#------------------------------------------------------------------------------
# Phase 1 : level points
#------------------------------------------------------------------------------

update game_character
  set point = 5 * (inventory - 4) + (inventory - 4) * (inventory - 5) / 2
  where inventory > 4 ;

#------------------------------------------------------------------------------
# Phase 2 : min inventory = 5
#------------------------------------------------------------------------------

update game_character
  set inventory = 5
  where inventory < 5 ;