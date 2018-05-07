
#------------------------------------------------------------------------------
# Outil pour construire les batiments spéciaux
#------------------------------------------------------------------------------

insert into game_skill_tool
    (`skill`, `item`, `coef`)
select
    s.id, i.id, 1
from
    game_skill_skill as s,
    game_ressource_item as i
where
    s.name = "BuildMisc" and
    i.name = "ToolBuilder" ;
