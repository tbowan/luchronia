#------------------------------------------------------------------------------
# Vitesse pour le temps dans les bonus
#------------------------------------------------------------------------------

ALTER TABLE game_modifier 
    ADD `speed` int(11) NOT NULL,
    ADD `price` int(11)     NULL ;
