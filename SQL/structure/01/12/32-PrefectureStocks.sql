
#------------------------------------------------------------------------------
# Les préfectures ont maintenant du stock
#------------------------------------------------------------------------------

update game_building_job set stock = 5 where `name` = "Prefecture" ;
