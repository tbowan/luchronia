#------------------------------------------------------------------------------
# I. Colonne des stocks pour connaitre le nombre d'items possibles
#------------------------------------------------------------------------------

ALTER TABLE game_building_job
    ADD stock int(11) NOT NULL ;

#------------------------------------------------------------------------------
# II. Valeur de la colonne :
#     - tous : 0
#     - Hôtel de ville : 5
#     - Entrepôt : 50
#     - Bibliothèque : 50 (dont 1/5 sont publiables)
#------------------------------------------------------------------------------

update game_building_job
    set stock = 0 ;

update game_building_job
    set stock = 5
    where `name` = "TownHall" ;

update game_building_job
    set stock = 50
    where `name` = "Storehouse" ;

update game_building_job
    set stock = 50
    where `name` = "Library" ;
