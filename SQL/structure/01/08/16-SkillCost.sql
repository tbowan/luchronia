
#------------------------------------------------------------------------------
# I. Colonne pour le coût des compétences
#------------------------------------------------------------------------------

ALTER TABLE game_skill_skill
    ADD cost int(11) not null DEFAULT -1 ;

#------------------------------------------------------------------------------
# II.1 coût générique pour les compétences
#------------------------------------------------------------------------------

# Toutes : -1
update game_skill_skill set cost = -1 ;
# Données au départ : 0
update game_skill_skill set cost = 0 where `start` ;
# Achetable : 1
update game_skill_skill set cost = 1 where `buyable` ;

#------------------------------------------------------------------------------
# II.2 coût spécifiques pour les compétences
#------------------------------------------------------------------------------

# Pas besoin car déjà traité dans les lignes précédentes :
# - Barricade
# - Learn
# - Move
# - Primary

# Enseigner d'après une caractéristique
update game_skill_skill set cost = 1 where `classname` = "Teach"            and cost = -1 ;

# fouilles archéologiques
update game_skill_skill set cost = 1 where `classname` = "Search"           and cost = -1 ;
update game_skill_skill set cost = 1 where `classname` = "Digout"           and cost = -1 ;

# Architecte, C'est plus cher que ça en a l'air car ...
# a. chaque compétence est déclinée par type de bâtiment (donc 7)
# b. débloquer un plan nécessite aussi de pouvoir faire les recherches qui vont avec

update game_skill_skill set cost = 1 where `classname` = "Build"            and cost = -1 ;
update game_skill_skill set cost = 1 where `classname` = "StudyBuilding"    and cost = -1 ;
update game_skill_skill set cost = 1 where `classname` = "StudyMap"         and cost = -1 ;
update game_skill_skill set cost = 1 where `classname` = "Research"         and cost = -1 ;

# Tracer de plans : points de niveau = tech / 50
# 2 pts : base et primaire
# 5 pts : secondaire + mur
# 6 pts : site de fouille et comptoir
# 8 pts : services + cokerie et haut fourneau
# 10 pts : préfecture
# 12 pts : hopital, académie, bourse
# 20 pts : palait et spacioport

update
    game_skill_skill
join
    game_building_job
on
    game_skill_skill.`name` like concat("DrawMap", game_building_job.`name`, "%")
set
    game_skill_skill.cost = game_building_job.technology / 50
where
    game_building_job.technology > 0 and
    game_skill_skill.cost = -1 ;

update
    game_skill_skill
join
    game_building_type
on
    game_skill_skill.`name` like concat("DrawMap%", game_building_type.`name`)
set
    game_skill_skill.cost = game_skill_skill.cost - 5 + game_building_type.technology / 0.2
where
    game_building_type.technology > 0 ;

# militaire
update game_skill_skill set cost = 1 where `classname` = "Fight"            and cost = -1 ;

# Primaire
update game_skill_skill set cost = 1 where `classname` = "FieldGather"      and cost = -1 ;

# Secondaire
# Dans un fichier annexe

#------------------------------------------------------------------------------
# III. Suppression des colonnes inutiles
#------------------------------------------------------------------------------

ALTER TABLE game_skill_skill
    DROP COLUMN `start` ,
    DROP COLUMN `buyable` ;