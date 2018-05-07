#------------------------------------------------------------------------------
# Ajout des outils par métiers
#------------------------------------------------------------------------------

insert into game_ressource_item
    (`name`, `groupable`)
select
    concat("Tool", m.`name`) as `name`,
    0 as `groupable`
from
    game_skill_metier as m
where
    `name` not in (
        "Militiaman",
        "Shooter",
        "Infantryman",
        "Student",
        "Professeur"
    ) ;

#------------------------------------------------------------------------------
# Ajout de l'utilisabilité des outils par métiers
#------------------------------------------------------------------------------

insert into game_skill_tool
    (`skill`, `item`, `coef`)
select
    s.id as `skill`,
    i.id as `item`,
    1.0 as `coef`
from
    game_ressource_item as i,
    game_skill_skill as s,
    game_skill_metier as m
where
    i.name = concat("TOOL", m.`name`) and
    s.metier = m.id
    ;
