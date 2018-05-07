
#------------------------------------------------------------------------------
# Invitations par code
#------------------------------------------------------------------------------

ALTER TABLE identity_sponsor
    ADD     `code`                  varchar(16) NOT NULL,
    CHANGE `sponsor`    `sponsor`   int(11)         NULL ;
