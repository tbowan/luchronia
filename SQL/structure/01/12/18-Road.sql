
#------------------------------------------------------------------------------
# Type de bâtiment "misc"
#------------------------------------------------------------------------------

insert into game_building_type (`name`, `wear`, `technology`, prestige, fitness) values
    ("Misc", 1, 1, 1, 1) ;

#------------------------------------------------------------------------------
# Fonction de bâtiment : route
#------------------------------------------------------------------------------

SET @Communication = (select id from game_politic_ministry where name = "Communication") ;

insert into game_building_job (`name`, ministry, technology, health, `level`, stock, tradable, wear, prestige, fitness) values
    ("Road", @Communication, 100, 100, 5, 0, 0, 0.5, 1, 0) ;

SET @Road = LAST_INSERT_ID() ;

#------------------------------------------------------------------------------
# Coût de construction d'un raiseau routier
#------------------------------------------------------------------------------

SET @Clay  = (select id from game_ressource_item where name = "Clay") ;
SET @Stone = (select id from game_ressource_item where name = "LimeStone") ;

insert into game_building_complement (item, job, amount) values
    (@Clay,  @Road, 25),
    (@Stone, @Road, 50) ;
