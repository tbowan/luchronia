
#------------------------------------------------------------------------------
# Ajout de la quantité de quantas
#------------------------------------------------------------------------------

ALTER TABLE identity_user
    ADD quanta double not null ;

#------------------------------------------------------------------------------
# Les achats de quantas
#------------------------------------------------------------------------------

CREATE TABLE `ecom_quanta` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `timestamp`     int(11)     NOT NULL,
    `user`          int(11)     NOT NULL,
    `ip`            varchar(32) NOT NULL,
    `amount`        int(11)      NOT NULL,
    `type`          varchar(32) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`)    REFERENCES `identity_user` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Achat via Allopass
#------------------------------------------------------------------------------

INSERT INTO quantyl_config (`key`, `type`, `value`) values
    ("ALLOPASS_SITE_ID", "Text", "") ;

CREATE TABLE `ecom_allopass_product` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `name`          varchar(32) NOT NULL,
    `amount`        int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `ecom_allopass_code` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `quanta`        int(11)     NOT NULL,
    `product`       int(11)     NOT NULL,
    `code`          varchar(32) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`quanta`)    REFERENCES `ecom_quanta` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`product`)   REFERENCES `ecom_allopass_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ajout des taux de TVA
#------------------------------------------------------------------------------

CREATE TABLE `ecom_vat` (
    `id`    int(11)     NOT NULL AUTO_INCREMENT,
    `code`  varchar(3)  NOT NULL,
    `rate`  double      NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

insert into ecom_vat (`code`, `rate`) values
    ("DE",  0.19), # Allemagne
    ("AT",  0.20), # Autriche
    ("BE",  0.21), # Belgique
    ("BG",  0.20), # Bulgarie
    ("CY",  0.19), # Chypre
    ("HR",  0.25), # Croatie
    ("DK",  0.25), # Danemark
    ("ES",  0.21), # Espagne
    ("EE",  0.20), # Estonie
    ("FI",  0.24), # Finlande
    ("FR",  0.20), # France
    ("EL",  0.23), # Grèce
    ("HU",  0.27), # Hongrie
    ("IE",  0.23), # Irlande
    ("IT",  0.22), # Italie
    ("LV",  0.21), # Lettonie
    ("LT",  0.21), # Lituanie
    ("LU",  0.15), # Luxembourg
    ("MT",  0.18), # Malte
    ("NL",  0.21), # Pays Bas
    ("PL",  0.23), # Pologne
    ("PT",  0.23), # Portugal
    ("RO",  0.24), # Roumanie
    ("UK",  0.20), # Royaume Unis
    ("SK",  0.20), # République Slovaque
    ("SI",  0.22), # Slovenie
    ("SE",  0.25), # Suède
    ("CZ",  0.21), # République Tchèque
    ("MCO", 0.20) # Monaco (même taux qu'en France)
# Jungholz et Mittleberg (0.19, territoire en autriche qui applique des taux allemands)
# Açores (code PT-20 - portugal) taux de 0.16
# Madère (code PT-30 - portugal) taux de 0.16
# Akrotiri (Base militaire britanique), taux de chypre (puisque c'est à chypre ...)
# Dhekelia (Base militaire britanique), taux de chypre (puisque c'est à chypre ...)
;

