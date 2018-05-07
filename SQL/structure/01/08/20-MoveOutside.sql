#------------------------------------------------------------------------------
# I. Now, moving is outside
#------------------------------------------------------------------------------

SET @Outside = (SELECT id FROM `game_building_job` WHERE `name` = "Outside") ;


update game_skill_skill
    set building_job = @Outside
    where classname = "Move" ;
