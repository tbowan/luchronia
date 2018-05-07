
#------------------------------------------------------------------------------
# Suppression de notifications jugées inutiles
#------------------------------------------------------------------------------

ALTER TABLE identity_user
    DROP mailon_forum_thread,
    DROP mailon_forum_answer;