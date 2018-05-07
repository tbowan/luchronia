
#------------------------------------------------------------------------------
# Coefficient "by_hand" à 0.5 au minimum
#------------------------------------------------------------------------------

update game_skill_skill
    set by_hand = 0.5
    where
        isnull(by_hand) and
        classname = "Primary"
        ;
  