
#------------------------------------------------------------------------------
# Bonus des objets équipables
#------------------------------------------------------------------------------

ALTER TABLE game_ressource_equipable
    ADD discretion  int(11) not null,
    ADD defense     int(11) not null,
    ADD resistance  int(11) not null,
    ADD impact      int(11) not null ;
