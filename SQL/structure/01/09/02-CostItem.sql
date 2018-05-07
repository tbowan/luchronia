

alter table game_skill_primary add `coef` double not null ;

#------------------------------------------------------------------------------
# Valeur par défaut
#------------------------------------------------------------------------------

update game_skill_skill   set by_hand = 1.0 where by_hand > 0 ;
update game_skill_primary set `coef`  = 1.0 ;
update game_skill_tool    set `coef`  = 1.0 ;

#------------------------------------------------------------------------------
# Puiser de l'eau
#------------------------------------------------------------------------------

update game_skill_primary
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_primary.skill
    set `coef` = 10.0
    where game_skill_skill.`name` = "WellWater" ;

#------------------------------------------------------------------------------
# Bois et Charbon
#------------------------------------------------------------------------------

# Extraction du charbon

update game_skill_primary
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_primary.skill
    set `coef` = 2.0
    where game_skill_skill.`name` = "MineCoal" ;

# Pyrolyse de bois

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 4.0
    where game_skill_skill.`name` = "PyrolyzingBet" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 5.0
    where game_skill_skill.`name` = "PyrolyzingKver" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 4.0
    where game_skill_skill.`name` = "PyrolyzingBao" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 4.0
    where game_skill_skill.`name` = "PyrolyzingPin" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 1.0
    where game_skill_skill.`name` = "PyrolyzingAbi" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 3.0
    where game_skill_skill.`name` = "PyrolyzingLarik" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 2.0
    where game_skill_skill.`name` = "PyrolyzingSpruc" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 1.0
    where game_skill_skill.`name` = "PyrolyzingOli" ;

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 2.0
    where game_skill_skill.`name` = "PyrolyzingSalik" ;

#------------------------------------------------------------------------------
# Bûcheron : couper le bois
#------------------------------------------------------------------------------

update game_skill_skill
    set by_hand = null
    where `name` like "Cut%" ;

#------------------------------------------------------------------------------
# Scieur : coût des planches
#------------------------------------------------------------------------------

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = ceiling(`amount` / 3.0)
    where game_skill_skill.`name` like "Saw%" ;

#------------------------------------------------------------------------------
# coût du pain
#------------------------------------------------------------------------------

update game_skill_out
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_out.skill
    set `amount` = 12
    where game_skill_skill.`name` like "%Bread%" ;

#------------------------------------------------------------------------------
# Sable, argile et minerais de fer
#------------------------------------------------------------------------------

update game_skill_primary
    inner join game_skill_skill
    on game_skill_skill.id = game_skill_primary.skill
    set `coef` = 2.0
    where
        game_skill_skill.`name` = "QuarySand" OR
        game_skill_skill.`name` = "MineIronOre" OR
        game_skill_skill.`name` = "QuaryClay"
        ;
