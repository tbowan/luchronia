#------------------------------------------------------------------------------
# Ajout de la colone des dns pour les langues
#------------------------------------------------------------------------------

ALTER TABLE i18n_lang
    ADD dns   varchar(32) NOT NULL ;
