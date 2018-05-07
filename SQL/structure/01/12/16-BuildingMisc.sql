
#------------------------------------------------------------------------------
# Compétence pour construire des bâtiments "spéciaux"
#------------------------------------------------------------------------------

insert into game_skill_skill
    (`name`, classname, building_job, building_type, by_hand, characteristic, cost, metier)
select
    "BuildMisc", "Build", j.id, t.id, 0, c.id, 1, m.id
from
    game_building_job as j,
    game_building_type as t,
    game_characteristic as c,
    game_skill_metier as m
where
    j.name = "Site" and
    t.name = "Misc" and
    c.name = "Strength" and
    m.name = "Builder" ;

set @BuildMisc = LAST_INSERT_ID() ;

#------------------------------------------------------------------------------
# Parchemins
#------------------------------------------------------------------------------

insert into game_ressource_item (name) values ("ParchmentBuildMisc") ;
set @Parch = LAST_INSERT_ID() ;

insert into game_ressource_parchment (skill, item) values (@BuildMisc, @Parch) ;


#------------------------------------------------------------------------------
# Livres
#------------------------------------------------------------------------------

insert into game_ressource_item (name) values ("BookBuildMisc") ;
set @Book = LAST_INSERT_ID() ;

insert into game_ressource_book (skill, item) values (@BuildMisc, @Book) ;
