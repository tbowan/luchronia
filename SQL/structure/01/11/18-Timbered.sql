
#------------------------------------------------------------------------------
# Ajout des "fibres"
#------------------------------------------------------------------------------

insert into game_ressource_item (`name`, `groupable`) values ("Fiber", 1) ;

#------------------------------------------------------------------------------
# Ajout des compétences
#------------------------------------------------------------------------------

SET @mill       = (SELECT id from game_building_job     where name = "Mill"         ) ;
SET @strength   = (SELECT id from game_characteristic   where name = "Strength"     ) ;
SET @miller     = (SELECT id from game_skill_metier     where name = "Miller"       ) ;

# Compétences

insert into game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `cost`, `metier`)
values
    ("MakeGresboFiber", "Secondary", @mill, null, 0, @strength, 1, @miller),
    ("MakeAvoroFiber",  "Secondary", @mill, null, 0, @strength, 1, @miller) ;

# Outils

insert into game_skill_tool (`skill`, `item`, `coef`)
select
    sk.id, it.id, 1
from
    game_skill_skill as sk,
    game_ressource_item as it
where
    sk.name in ("MakeGresboFiber", "MakeAvoroFiber") and
    it.name = "ToolMiller" ;

# In and outs ---

insert into game_skill_in (`skill`, `item`, `amount`)
select
    sk.id, it.id, 1
from
    game_skill_skill as sk,
    game_ressource_item as it
where
    sk.name = "MakeGresboFiber" and
    it.name = "GresboFeed" ;

insert into game_skill_out (`skill`, `item`, `amount`)
select
    sk.id, it.id, 1
from
    game_skill_skill as sk,
    game_ressource_item as it
where
    sk.name = "MakeGresboFiber" and
    it.name = "Fiber" ;

# In and outs ---

insert into game_skill_in (`skill`, `item`, `amount`)
select
    sk.id, it.id, 3
from
    game_skill_skill as sk,
    game_ressource_item as it
where
    sk.name = "MakeAvoroFiber" and
    it.name = "AvoroStraw" ;

insert into game_skill_out (`skill`, `item`, `amount`)
select
    sk.id, it.id, 4
from
    game_skill_skill as sk,
    game_ressource_item as it
where
    sk.name = "MakeAvoroFiber" and
    it.name = "Fiber" ;

# Livres

insert into game_ressource_item (`name`, `groupable`)
select  concat("Book", sk.name), 0
from    game_skill_skill as sk
where   sk.name in ("MakeGresboFiber", "MakeAvoroFiber") ;

insert into game_ressource_book (`item`, `skill`)
select it.id, sk.id
from
    game_ressource_item as it,
    game_skill_skill as sk
where
    sk.name in ("MakeGresboFiber", "MakeAvoroFiber") and
    it.name = concat("Book", sk.name) ;

# Parchemins

insert into game_ressource_item (`name`, `groupable`)
select  concat("Parchment", sk.name), 0
from    game_skill_skill as sk
where   sk.name in ("MakeGresboFiber", "MakeAvoroFiber") ;

insert into game_ressource_parchment (`item`, `skill`)
select it.id, sk.id
from
    game_ressource_item as it,
    game_skill_skill as sk
where
    sk.name in ("MakeGresboFiber", "MakeAvoroFiber") and
    it.name = concat("Parchment", sk.name) ;

#------------------------------------------------------------------------------
# Ajout des ressources pour construire les bâtiments
#------------------------------------------------------------------------------
SET @timbered     = (SELECT id from game_building_type     where name = "Timbered"       ) ;

insert into game_building_construction (`item`, `type`, `amount`)
select it.id, @timbered, 0.5 from game_ressource_item as it where name = "Plank" union
select it.id, @timbered, 0.2 from game_ressource_item as it where name = "Fiber" union
select it.id, @timbered, 0.1 from game_ressource_item as it where name = "Water" union
select it.id, @timbered, 0.5 from game_ressource_item as it where name = "Clay" ;


