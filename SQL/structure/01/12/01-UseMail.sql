
#------------------------------------------------------------------------------
# Validation de l'adresse mail des utilisateurs
#------------------------------------------------------------------------------

ALTER TABLE identity_user 
    ADD `email_valid` tinyint(1)  NOT NULL,
    ADD `email_token` varchar(32)     NULL ;
