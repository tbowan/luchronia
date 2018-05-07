

#------------------------------------------------------------------------------
# Quantité entière pour les utilisateurs
#------------------------------------------------------------------------------

ALTER TABLE ecom_allopass_product
    ADD `idd` varchar(12) not null ;

INSERT INTO quantyl_config (`key`, `type`, `value`) values
    ("ALLOPASS_AUTH", "Text", "") ;

ALTER TABLE ecom_allopass_code
    ADD `trxid` varchar(40) not null ;

