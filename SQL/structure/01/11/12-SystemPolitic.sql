

#------------------------------------------------------------------------------
# Les questions qui sont déjà traitées par le système
#------------------------------------------------------------------------------

ALTER TABLE game_politic_system
    ADD `question` int(11) NULL,
    ADD FOREIGN KEY (`question`)  REFERENCES `game_politic_question` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ;