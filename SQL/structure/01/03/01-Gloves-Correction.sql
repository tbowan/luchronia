
SET @Gloves       = (SELECT id FROM `game_ressource_slot` WHERE `name` = "Gloves") ;

update game_ressource_equipable set amount = 1 where slot = @Gloves ;

