

#------------------------------------------------------------------------------
# Invitations par code
#------------------------------------------------------------------------------

ALTER TABLE game_building_job ADD `wear` double NOT NULL ;

update game_building_job set `wear` = 1.0 ;
update game_building_job set `wear` = 0.5 where `name` = "Wall" ;
update game_building_job set `wear` = 5.0 where `name` = "Site" ;

