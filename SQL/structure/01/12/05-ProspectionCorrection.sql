
#------------------------------------------------------------------------------
# Ces compétences ont lieu dehors
#------------------------------------------------------------------------------

SET @Outside  = (SELECT id from game_building_job   where name = "Outside"  ) ;

update
    game_skill_skill
set
    building_job = @Outside
where
    name in ("ProspectGround", "ProspectUnderground", "ProspectArcheo") ;

