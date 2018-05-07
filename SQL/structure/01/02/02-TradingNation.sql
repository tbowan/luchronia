#------------------------------------------------------------------------------
# Trading for nations
#------------------------------------------------------------------------------

#------------------------------------------------------------------------------
# Prices for goodies
#------------------------------------------------------------------------------

create table game_trading_nation (
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `nation`  int(11)     NULL,
  `item`    int(11) NOT NULL,
  `price`   double  NOT NULL,
  `amount`  int(11)     NULL,
  `type`    int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`nation`)    REFERENCES `game_city` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`item`)      REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Taxes in trading posts
#------------------------------------------------------------------------------

create table game_building_tradingpost (
  `id`          int(11) NOT NULL AUTO_INCREMENT,
  `instance`    int(11) NOT NULL,
  `tax`         double  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# goodies actualy buyable and sellable in cities
#------------------------------------------------------------------------------

create table game_trading_tradingpost (
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `instance` int(11)     NULL,
  `trading`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`trading`)     REFERENCES `game_trading_nation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
