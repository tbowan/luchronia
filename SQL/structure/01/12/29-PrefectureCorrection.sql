
#------------------------------------------------------------------------------
# Les colonnes de pourcentages sont des doubles
#------------------------------------------------------------------------------

ALTER TABLE game_building_prefecture
    change prestige_in  prestige_in  double not null,
    change prestige_out prestige_out double not null ;
