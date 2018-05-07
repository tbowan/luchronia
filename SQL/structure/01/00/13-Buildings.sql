
#------------------------------------------------------------------------------
# Building Maps
#------------------------------------------------------------------------------

CREATE TABLE `game_building_map` (
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `item`  int(11) NOT NULL,
    `job`   int(11) NOT NULL,
    `type`  int(11) NOT NULL,
    `level` int(11) NOT NULL,
    `tech`  double  NOT null,
    `skill` int(11) NOT null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item`) REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`job`)  REFERENCES `game_building_job`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`type`) REFERENCES `game_building_type`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`)
        REFERENCES `game_skill_skill` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Building jobs
#------------------------------------------------------------------------------

SET @Homeland       = (SELECT id FROM `game_politic_ministry` WHERE `name` = "HomeLand") ;
SET @State          = (SELECT id FROM `game_politic_ministry` WHERE `name` = "State") ;
SET @Commerce       = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Commerce") ;
SET @Communication  = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Communication") ;
SET @Agriculture    = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Agriculture") ;
SET @Agriculture    = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Agriculture") ;
SET @Labor          = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Labor") ;
SET @Health         = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Health") ;
SET @Education      = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Education") ;
SET @Development    = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Development") ;
SET @Defense        = (SELECT id FROM `game_politic_ministry` WHERE `name` = "Defense") ;

INSERT INTO `game_building_job` (`name`, `ministry`, `technology`, `health`) VALUES
    ("Outside", @Defense,     0, 0),
    ("Site",    @Development, 0, 0),
    ("Ruin",    @Development, 0, 0),
    ("Free",    @Development, 0, 0),

    ("TownHall",    @Homeland,  100, 100),
    ("Prefecture",  @State,     500, 200),
    ("Palace",      @State,    1000, 500),
    ("TradingPost", @State,     300,  50),

    ("Woodcutter", @Agriculture, 100,  25),
    ("Gatherer",   @Agriculture, 100,  25),
    ("Quary",      @Labor,       100,  25),
    ("Mine",       @Labor,       100, 100),
    ("Well",       @Agriculture, 100,  25),
    ("Field",      @Agriculture, 100,  50),
    ("Storehouse", @Commerce,    100, 100),

    ("Druid",        @Health,      250,  25),
    ("Kitchen",      @Agriculture, 250,  50),
    ("Sawmill",      @Labor,       250,  25),
    ("Coal",         @Labor,       200,  25),
    ("Cokery",       @Labor,       400, 100),
    ("LowFurnace",   @Labor,       250,  75),
    ("BlastFurnace", @Labor,       400, 100),
    ("Forge",        @Labor,       250,  75),
    ("Drying",       @Agriculture, 250,  50),
    ("Basketry",     @Labor,       250,  25),
    ("Mill",         @Agriculture, 250,  75),
    ("Glassware",    @Labor,       250,  50),
    ("Brickyard",    @Labor,       250,  75),
    ("Weaver",       @Labor,       250,  25),
    ("Brewery",      @Agriculture, 250,  50),

    ("Market",   @Commerce,      400, 100),
    ("Exchange", @Commerce,      600, 200),
    ("Tavern",   @Agriculture,   400,  50),
    ("Forum",    @Communication, 400,  50),
    ("Post",     @Communication, 400,  25),
    ("Hospital", @Health,        600, 200),

    ("Architect",  @Education, 250, 50),
    ("Excavation", @Education, 300, 25),
    ("Library",    @Education, 400, 75),
    ("Academy",    @Education, 600, 200),

    ("Spaceport", @Communication, 1000, 200),

    ("Wall", @Defense, 250, 2000) ;

#------------------------------------------------------------------------------
# Building Type
#------------------------------------------------------------------------------

INSERT INTO `game_building_type` (`name`, `wear`, `technology`) VALUES
    ("Clay",     0.5, 1),
    ("Timbered", 0.4, 1.2),
    ("Wood",     0.3, 1.4),
    ("Stone",    0.1, 1.6),
    ("Brick",    0.2, 1.8),
    ("Steel",    0.1, 2),
    ("Glass",    0.4, 2.2) ;