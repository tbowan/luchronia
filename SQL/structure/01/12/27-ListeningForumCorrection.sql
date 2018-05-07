#------------------------------------------------------------------------------
# Correction d'une erreur dans le fichier 25-ListeningForum
#------------------------------------------------------------------------------

update event_listening set listener="SocialGameForumFollow" where event="Social_Game_Forum_Follow";
