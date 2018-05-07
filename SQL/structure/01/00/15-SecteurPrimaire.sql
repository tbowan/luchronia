#------------------------------------------------------------------------------
# Les objets
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_primary` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `skill`      int(11)     NOT NULL,
  `in`       int(11)     NOT NULL,
  `out`       int(11)     NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`in`)    REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`out`)   REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

INSERT INTO game_ressource_item
    (`name`, `groupable`)
    VALUES

# 1 - Arbres
    ("Bet",   1),
    ("Salik", 1),
    ("Kver",  1),
    ("Spruc", 1),
    ("Larik", 1),
    ("Pin",   1),
    ("Abi",   1),
    ("Bao",   1),
    ("Oli",   1),

# 1.2 - Bois des arbres
    ("BetTimber",   1),
    ("SalikTimber", 1),
    ("KverTimber",  1),
    ("SprucTimber", 1),
    ("LarikTimber", 1),
    ("PinTimber",   1),
    ("AbiTimber",   1),
    ("BaoTimber",   1),
    ("OliTimber",   1),

# 1.3 - Fruits des arbres
    ("PinFruit", 1), # Piño
    ("AbiFruit", 1), # Bourgeons
    ("BaoFruit", 1), # Adano
    ("OliFruit", 1), # Olio

# 2 - Herbacées
    ("Jarkilo", 1),
    ("Gresbo",  1),
    ("Avoro",   1),
    ("Ligio",   1),
    ("Flento",  1),

# 2.1 - Tiges, plants, ...
#       Stem = Tige
#       Feed = Fourrage
#       Plant = Plant
#       Flower = fleur
    ("JarkiloStem",  1),
    ("GresboFeed",   1),
    ("AvoroStem",    1),
    ("LigioPlant",   1),
    ("FlentoFlower", 1),

# 3 - Cryptogames
    ("Lichoj", 1),
    ("Somo",   1),
    ("Fiko",   1),

# 3.1 - Plants, tiges, ...
#        Moss = Mousse
    ("LichojPlant", 1),
    ("SomoMoss",    1),
    ("FikoPlant",   1),

# 4 - Arbustes
    ("Bero",   1),
    ("Thorno", 1),
    ("Bailo",  1),
    ("Eiko",   1),
    ("Rorro",  1),
    ("Lavo",   1),

# 4.1 - plants, fruits, ...
    ("BeroFruit",   1), # Airelles
    ("ThornoFruit", 1), # Baies
    ("BailoPlant",  1),
    ("EikoPlant",   1),
    ("RorroPlant",  1),
    ("LavoPlant",   1),

# 5 - Legumineuse
    ("Arido", 1),
    ("Beano", 1),

# 5.1 - plants
    ("AridoSeed", 1), # graines
    ("BeanoHull", 1), # Cosses

# 6 - Plantes grasses
    ("Kakto",     1),
    ("Aloe",      1),
    ("Bromelio",  1),
    ("Squo",      1),
    ("Echevo",    1),
    ("Fangsorxo", 1),

# 6.1 - Plants ...
    ("KaktoPlant",     1),
    ("AloePlant",      1),
    ("BromelioPlant",  1),
    ("SquoPlant",      1),
    ("EchevoFlower",   1),
    ("FangsorxoFruit", 1),

# 7 - Carrière
    ("Sand",      1), # sable
    ("Clay",      1), # argile
    ("LimeStone", 1), # calcaire

# 8 - Mine
    ("Coal",    1), # Houille
    ("IronOre", 1), # minerai de fer

# 9 - Well
    ("Water", 1), # eau

# 2 - Outils
    ("SawOneHand",    0), # Scie à une main
    ("Saw",           0), # Scie
    ("Axe",           0), # Hache
    ("Serpe",         0), # Serpe
    ("Secateur",      0), # Sécateur
    ("Scythe",        0), # Faux
    ("Knife",         0), # Couteau
    ("GathererKnife", 0), # Couteau de cueilleur
    ("Glove",         0), # Gants
    ("Comb",          0), # Peigne
    ("Shovel",        0), # Pelle
    ("Pickaxe",       0), # Pioche
    ("Dynamite",      0), # Dynamite
    ("SealLiquid",    0), # Seau (contenant)
    ("Gourd",         0)  # Gourde
;

#------------------------------------------------------------------------------
# Characteristics
#------------------------------------------------------------------------------

INSERT INTO game_characteristic
    (`name`)
VALUES
    ("Strength"),
    ("Mental"),
    ("Perception"),
    ("Charisma")
;
