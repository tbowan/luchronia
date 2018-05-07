#------------------------------------------------------------------------------
# I. Table des métiers
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_metier` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `name`      varchar(32) NOT NULL,
    `ministry`  int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ministry`)  REFERENCES `game_politic_ministry` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# II. Liste des métiers
#------------------------------------------------------------------------------

SET @Homeland       = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Homeland") ;
SET @State          = (SELECT id FROM `game_politic_ministry` WHERE `name` = "State") ;
SET @Commerce       = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Commerce") ;
SET @Communication  = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Communication") ;
SET @Agriculture    = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Agriculture") ;
SET @Labor          = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Labor") ;
SET @Health         = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Health") ;
SET @Education      = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Education") ;
SET @Development    = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Development") ;
SET @Defense        = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Defense") ;

INSERT INTO game_skill_metier (`name`, `ministry`)
values
    ("WoodCutter",      @Agriculture), # Bucheron
    ("Gatherer",        @Agriculture), # Cuilleur
    ("Reaper",          @Agriculture), # Moissonneur
    ("Herbalist",       @Agriculture), # Herboriste
    ("Carrier",         @Labor), # Carrier
    ("Miner",           @Labor), # Mineur
    ("WellDigger",      @Agriculture), # Puisatier
    ("Farmer",          @Agriculture), # Agriculteur
    ("Druid",           @Health), # Druide
    ("Alchemist",       @Health), # Alchimiste
    ("Apothecary",      @Health), # Apoticaire
    ("Creamer",         @Agriculture), # Crémier
    ("Confectioner",    @Agriculture), # Confiseur
    ("PastryMaker",     @Agriculture), # Boulanger Pâtissier
    ("Cook",            @Agriculture), # Cuisinier
    ("WoodSawyer",      @Labor), # Scieur de bois
    ("WoodTurner",      @Labor), # Tourneur sur bois
    ("Carpenter",       @Labor), # menuisier
    ("Coal",            @Labor), # Charbonier
    ("SteelMaker",      @Labor), # Sidérurgiste
    ("Smith",           @Labor), # Forgeron
    ("Dryer",           @Agriculture), # Déshydrateur
    ("BasketMaker",     @Labor), # Vannier
    ("Miller",          @Agriculture), # Meunier
    ("GlassMaker",      @Labor), # Souffleur de verre
    ("BrickMaker",      @Labor), # Briquetier
    ("Weaver",          @Labor), # Tisserand
    ("Tailor",          @Labor), # Tailleur (vêtement)
    ("Brewer",          @Agriculture), # Brasseur
    ("Refiner",         @Agriculture), # Raffineur
    ("Militiaman",      @Defense), # Milicien
    ("Shooter",         @Defense), # Tireur (on aurait aussi pu utiliser : gunman, bowman, sniper)
    ("Infantryman",     @Defense), # Fantassin
    ("Doctor",          @Health), # Médecin
    ("Architect",       @Education), # Architecte
    ("Builder",         @Development), # Bâtisseur
    ("Archaeologist",   @Education), # Archéologue
    ("Student",         @Education), # Etudiant
    ("Professeur",      @Education), # Professeur
    ("Explorer",        @Education); # Explorateur

#------------------------------------------------------------------------------
# III. Association entre compétence et métiers
#------------------------------------------------------------------------------

ALTER TABLE game_skill_skill
    ADD metier int(11) NULL,
    ADD FOREIGN KEY (`metier`)  REFERENCES `game_skill_metier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "WoodCutter") as m
SET metier = m.id
WHERE
    `name` like "%Cut%" and
    `classname` in ("Primary", "FieldGather") ;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Gatherer") as m
SET metier = m.id
WHERE
    `name` in (
        "GatherPin",
        "GatherAbi",
        "GatherBao",
        "GatherOli",
        "GatherBero",
        "GatherThorno",
        "GatherBailo",
        "GatherEiko",
        "GatherRorro",
        "GatherLavo",
        "GatherArido",
        "GatherBeano"
        ) ;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Reaper") as m
SET metier = m.id
WHERE
    `name` in (
        "GatherJarkilo",
        "GatherGresbo",
        "GatherAvoro",
        "GatherLigio",
        "GatherFlento"
        ) ;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Herbalist") as m
SET metier = m.id
WHERE
    `name` in (
        "GatherLichoj",
        "GatherSomo",
        "GatherFiko",
        "GatherKakto",
        "GatherAloe",
        "GatherBromelio",
        "GatherSquo",
        "GatherEchevo",
        "GatherFangsorxo"
        ) ;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Carrier") as m
SET metier = m.id
WHERE
    `name` in (
        "QuarySand",
        "QuaryClay",
        "QuaryLimeStone"
        ) ;

#--------------------------------

SET @Mine       = (SELECT id FROM `game_building_job` WHERE `name` = "Mine") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Miner") as m
SET metier = m.id
WHERE
    `building_job` = @Mine and
    isnull(`metier`)
;


#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "WellDigger") as m
SET metier = m.id
WHERE
    `name` in (
        "WellWater"
        ) ;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Farmer") as m
SET metier = m.id
WHERE
    `classname` = "FieldGather" and
    isnull(`metier`)
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Druid") as m
SET metier = m.id
WHERE
    `name` like "%balm%" or
    `name` like "%FatBasis%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Alchemist") as m
SET metier = m.id
WHERE
    `name` like "%sirup%" or
    `name` like "%SugarBasis%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Apothecary") as m
SET metier = m.id
WHERE
    `name` like "%Pill%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Creamer") as m
SET metier = m.id
WHERE
    `name` like "%churning%" or
    `name` like "%milk%" or
    `name` like "%cheese%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Confectioner") as m
SET metier = m.id
WHERE
    `name` like "%candied%" or
    `name` like "%candy%" or
    `name` like "%jam%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "PastryMaker") as m
SET metier = m.id
WHERE
    `name` like "%smoothie%" or
    `name` like "%bread%" or
    `name` like "%cake%"
;

#--------------------------------
SET @Kitchen       = (SELECT id FROM `game_building_job` WHERE `name` = "Kitchen") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Cook") as m
SET metier = m.id
WHERE
    `building_job` = @Kitchen and
    isnull(`metier`)
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "WoodSawyer") as m
SET metier = m.id
WHERE
    `name` like "saw%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "WoodTurner") as m
SET metier = m.id
WHERE
    `name` like "%woodpot%" or
    `name` like "%spoon%" or
    `name` like "%bowl%" or
    `name` like "%bolus%" or
    `name` like "%plate%"
;

#--------------------------------

SET @Sawmill       = (SELECT id FROM `game_building_job` WHERE `name` = "Sawmill") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Carpenter") as m
SET metier = m.id
WHERE
    `building_job` = @Sawmill and
    isnull(`metier`)
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Coal") as m
SET metier = m.id
WHERE
    `name` like "%Pyrolyzing%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "SteelMaker") as m
SET metier = m.id
WHERE
    `name` in (
        "MakeSteel",
        "MakeCastIron",
        "ProduceSteel",
        "ProduceCastIron")
;

#--------------------------------

SET @Forge       = (SELECT id FROM `game_building_job` WHERE `name` = "Forge") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Smith") as m
SET metier = m.id
WHERE
    `building_job` = @Forge and
    isnull(`metier`)
;

#--------------------------------

SET @Drying       = (SELECT id FROM `game_building_job` WHERE `name` = "Drying") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Dryer") as m
SET metier = m.id
WHERE
    `building_job` = @Drying and
    isnull(`metier`)
;

#--------------------------------

SET @Basketry       = (SELECT id FROM `game_building_job` WHERE `name` = "Basketry") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "BasketMaker") as m
SET metier = m.id
WHERE
    `building_job` = @Basketry and
    isnull(`metier`)
;

#--------------------------------

SET @Mill       = (SELECT id FROM `game_building_job` WHERE `name` = "Mill") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Miller") as m
SET metier = m.id
WHERE
    `building_job` = @Mill and
    isnull(`metier`)
;

#--------------------------------

SET @Glassware       = (SELECT id FROM `game_building_job` WHERE `name` = "Glassware") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "GlassMaker") as m
SET metier = m.id
WHERE
    `building_job` = @Glassware and
    isnull(`metier`)
;

#--------------------------------

SET @Brickyard       = (SELECT id FROM `game_building_job` WHERE `name` = "Brickyard") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "BrickMaker") as m
SET metier = m.id
WHERE
    `building_job` = @Brickyard and
    isnull(`metier`)
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Weaver") as m
SET metier = m.id
WHERE
    `name` in (
        "MakeEikoCloth",
        "MakeSomoCloth",
        "ProduceSteel",
        "MakeBromelioCloth")
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Tailor") as m
SET metier = m.id
WHERE
    `name` in ("MakeGlove")
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Refiner") as m
SET metier = m.id
WHERE
    `name` like "Refine%"
;

#--------------------------------

SET @Brewery       = (SELECT id FROM `game_building_job` WHERE `name` = "Brewery") ;

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Brewer") as m
SET metier = m.id
WHERE
    `building_job` = @Brewery and
    isnull(`metier`)
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Militiaman") as m
SET metier = m.id
WHERE
    `name` in (
        "Fight",
        "Barricade")
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Shooter") as m
SET metier = m.id
WHERE
    `name` in (
        "Archery",
        "Crossbow",
        "Pistol",
        "Rifle")
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Infantryman") as m
SET metier = m.id
WHERE
    `name` in (
        "Bayonet",
        "Cane",
        "Foil",
        "Sword",
        "Saber")
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Architect") as m
SET metier = m.id
WHERE
    `name` like "Study%" or
    `name` like "Research%" or
    `name` like "DrawMap%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Builder") as m
SET metier = m.id
WHERE
    `name` like "Build%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Archaeologist") as m
SET metier = m.id
WHERE
    `name` like "Search%" or
    `name` = "Digout"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Student") as m
SET metier = m.id
WHERE
    `name` like "Learn%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Professeur") as m
SET metier = m.id
WHERE
    `name` like "Teach%"
;

#--------------------------------

UPDATE game_skill_skill
INNER JOIN (select id from game_skill_metier where name = "Explorer") as m
SET metier = m.id
WHERE
    `classname` = "Move"
;
