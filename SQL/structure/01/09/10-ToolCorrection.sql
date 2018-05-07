

#------------------------------------------------------------------------------
# Création de l'outil du pharmacien
#------------------------------------------------------------------------------

insert into game_ressource_item
    (`name`, `groupable`, `energy`)
values
    ("ToolPharmacist", 0, 1000) ;

#------------------------------------------------------------------------------
# Remplacement des anciens outils
#------------------------------------------------------------------------------

SET @toolpharmacist = (SELECT id FROM `game_ressource_item` WHERE `name` = "ToolPharmacist") ;

update game_skill_tool
    inner join game_ressource_item
    on game_ressource_item.id = game_skill_tool.item
    set game_skill_tool.`item` = @toolpharmacist
    where game_ressource_item.`name` in (
        "ToolDruid",
        "ToolAlchemist",
        "ToolApothecary"
        ) ;

#------------------------------------------------------------------------------
# suppression des anciens outils
#------------------------------------------------------------------------------

delete from game_ressource_item
    where name in (
        "ToolDruid",
        "ToolAlchemist",
        "ToolApothecary"
        ) ;

#------------------------------------------------------------------------------
# suppression des traductions
#------------------------------------------------------------------------------

delete from i18n_translation
    where `key` in (
        "ITEM_ToolDruid",
        "ITEM_ToolAlchemist",
        "ITEM_ToolApothecary"
        ) ;
