
#------------------------------------------------------------------------------
# Slots utilisés par les types
#------------------------------------------------------------------------------

ALTER TABLE game_building_type
    add slot int(8) not null ;

update game_building_type set slot = 1 where `name` != "Misc" ;
