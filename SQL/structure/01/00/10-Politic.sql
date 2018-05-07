#------------------------------------------------------------------------------
# Politic : Système de gouvernement pour les villes
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_system` (
  `id`    int(11)     NOT NULL AUTO_INCREMENT,
  `city`  int(11)     NOT NULL,
  `type`  int(11)     NOT NULL,
  `name`  varchar(32) NOT NULL,
  `start` int(11)     NULL,
  `end`   int(11)     NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city`) REFERENCES `game_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Votes
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_question` (
  `id`     int(11)     NOT NULL AUTO_INCREMENT,
  `system` int(11)     NOT NULL,
  `type`   int(11)     NOT NULL,
  `vote`   int(11)     NOT NULL,
  `start`  int(11)     NULL,
  `end`    int(11)     NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`) REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_vote` (
  `id`        int(11)     NOT NULL AUTO_INCREMENT,
  `question`  int(11)     NOT NULL,
  `character` int(11)     NOT NULL,
  `value`     int(11)     NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question`)  REFERENCES `game_politic_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_candidate` (
  `id`        int(11)    NOT NULL AUTO_INCREMENT,
  `question`  int(11)    NOT NULL,
  `character` int(11)    NOT NULL,
  `answer`    tinyint(1) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question`)  REFERENCES `game_politic_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_choice` (
  `id`        int(11) NOT NULL AUTO_INCREMENT,
  `candidate` int(11) NOT NULL,
  `character` int(11) NOT NULL,
  `value`     int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`candidate`) REFERENCES `game_politic_candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`         (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Government
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_government` (
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `system`   int(11) NOT NULL,
  `author`   int(11) NOT NULL,
  `question` int(11) NULL,
  `start`    int(11) NULL,
  `end`      int(11) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`)   REFERENCES `game_politic_system`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`author`)   REFERENCES `game_character`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`question`) REFERENCES `game_politic_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_ministry` (
  `id`        int(11)       NOT NULL AUTO_INCREMENT,
  `name`      varchar(16)   NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

INSERT INTO `game_politic_ministry` (`name`) values
    ("HomeLand"),
    ("State"),
    ("Commerce"),
    ("Communication"),
    ("Agriculture"),
    ("Labor"),
    ("Health"),
    ("Education"),
    ("Development"),
    ("Defense") ;


CREATE TABLE `game_politic_minister` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `ministry`   int(11) NOT NULL,
  `government` int(11) NOT NULL,
  `character`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ministry`)   REFERENCES `game_politic_ministry`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`government`) REFERENCES `game_politic_government` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`)  REFERENCES `game_character`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : changement de gouvernement
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_change` (
  `id`          int(11)    NOT NULL AUTO_INCREMENT,
  `system`      int(11)    NOT NULL,
  `proposed`    int(11)    NOT NULL,
  `character`   int(11)    NOT NULL,
  `question`    int(11)    NOT NULL,
  `message`     text       NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`)    REFERENCES `game_politic_system`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`proposed`)  REFERENCES `game_politic_system`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`question`)  REFERENCES `game_politic_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Revolution
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_revolution` (
  `id`        int(11)     NOT NULL AUTO_INCREMENT,
  `system`    int(11)     NOT NULL,
  `proposed`  int(11)     NOT NULL,
  `character` int(11)     NOT NULL, 
  `message`   text        NOT NULL,
  `secretid`  varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE(`secretid`),
  FOREIGN KEY (`system`)    REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`proposed`)  REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_support` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `revolution` int(11) NOT NULL,
  `character`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`revolution`) REFERENCES `game_politic_revolution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`)  REFERENCES `game_character`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Monarchy
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_monarchy` (
  `id`     int(11) NOT NULL AUTO_INCREMENT,
  `system` int(11) NOT NULL,
  `king`   int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`) REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`king`)   REFERENCES `game_character`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Senate
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_senate` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `system`      int(11) NOT NULL,
# senators
  `admission`   double  NOT NULL,
  `revocation`  double  NOT NULL,
# government
  gov_delay     int(11) NOT NULL,
  gov_quorum    double  NOT NULL,
  gov_threshold double  NOT NULL,
# system
  sys_delay     int(11) NOT NULL,
  sys_quorum    double  NOT NULL,
  sys_threshold double  NOT NULL,
# keys
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`) REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_senator` (
  `id`        int(11) NOT NULL AUTO_INCREMENT,
  `senate`    int(11) NOT NULL,
  `character` int(11) NOT NULL,
  `start`     int(11) NULL,
  `end`       int(11) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`senate`)    REFERENCES `game_politic_senate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_friend` (
  `id`     int(11) NOT NULL AUTO_INCREMENT,
  `target` int(11) NOT NULL,
  `source` int(11) NOT NULL,
  `value`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`target`)   REFERENCES `game_politic_senator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`source`)   REFERENCES `game_politic_senator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Republic
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_republic` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `system`      int(11) NOT NULL,
# power
  `duration`    int(11) NOT NULL,
# president
  pres_delay    int(11) NOT NULL,
  pres_type     int(11) NOT NULL,
  pres_point    int(11) NOT NULL,
# system
  sys_delay     int(11) NOT NULL,
  sys_quorum    double  NOT NULL,
  sys_threshold double  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`) REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_president` (
  `id`        int(11) NOT NULL AUTO_INCREMENT,
  `republic`  int(11) NOT NULL,
  `character` int(11) NOT NULL,
  `question`  int(11) NOT NULL,
  `start`     int(11) NULL,
  `end`       int(11) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`republic`)  REFERENCES `game_politic_republic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`) REFERENCES `game_character`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`question`)  REFERENCES `game_politic_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Parliamentary
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_parliamentary` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `system`      int(11) NOT NULL,
# power
  `duration`    int(11) NOT NULL,
  `seats`       int(11) NOT NULL,
# parliamentarian
  parl_delay    int(11) NOT NULL,
  parl_type     int(11) NOT NULL,
  parl_point    int(11) NOT NULL,
# government
  gov_delay     int(11) NOT NULL,
  gov_quorum    double  NOT NULL,
  gov_threshold double  NOT NULL,
# system
  sys_delay     int(11) NOT NULL,
  sys_quorum    double  NOT NULL,
  sys_threshold double  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`) REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_parliament` (
  `id`            int(11) NOT NULL AUTO_INCREMENT,
  `parliamentary` int(11) NOT NULL,
  `question`      int(11) NOT NULL,
  `start`         int(11) NULL,
  `end`           int(11) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`parliamentary`) REFERENCES `game_politic_parliamentary` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`question`)      REFERENCES `game_politic_question`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_politic_parliamentarian` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `parliament` int(11) NOT NULL,
  `character`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`parliament`) REFERENCES `game_politic_parliament` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`character`)  REFERENCES `game_character`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Politic : Democracy
#------------------------------------------------------------------------------

CREATE TABLE `game_politic_democracy` (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `system`      int(11) NOT NULL,
# government
  gov_delay     int(11) NOT NULL,
  gov_quorum    double  NOT NULL,
  gov_threshold double  NOT NULL,
# system
  sys_delay     int(11) NOT NULL,
  sys_quorum    double  NOT NULL,
  sys_threshold double  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`system`) REFERENCES `game_politic_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
