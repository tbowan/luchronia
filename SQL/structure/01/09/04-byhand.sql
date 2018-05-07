#------------------------------------------------------------------------------
# Changement compétences sans main nues
#------------------------------------------------------------------------------

update game_skill_skill
    set by_hand = 0
    where
        `classname` not in (
            "Move",
            "Barricade",
            "Fight",
            "Learn",
            "Teach"
            ) ;




