# Fonction d insertion d un modifier de caracteristique

CREATE PROCEDURE insert_carac_modifier (modifier_name VARCHAR(64), carac_name VARCHAR(64), bonus DOUBLE)
BEGIN
    DECLARE modifier_id, carac_id INT;
    SET modifier_id = (SELECT id from game_modifier where name=modifier_name);
    SET carac_id    = (SELECT id from game_characteristic where name=carac_name);
    INSERT into game_characteristic_modifier (modifier, characteristic, bonus) VALUES (modifier_id, carac_id, bonus) ;
END ;

INSERT INTO game_modifier  (name, health, duration)                    VALUES ("Recovery", 200, 0);
INSERT INTO game_modifier  (name, energy, hydration, health, duration) VALUES ("Comforting", 200, 200, 300, 0);
INSERT INTO game_modifier  (name, experience, duration)                VALUES ("Aura", 100000, 0);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Physic", 604800);
call insert_carac_modifier ("Physic", "Strength", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Charisma", 604800);
call insert_carac_modifier ("Charisma", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Natural", 604800);
call insert_carac_modifier ("Natural", "Strength", 1);
call insert_carac_modifier ("Natural", "Perception", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Intellection", 604800);
call insert_carac_modifier ("Intellection", "Strength", 1);
call insert_carac_modifier ("Intellection", "Mental", 1);
call insert_carac_modifier ("Intellection", "Perception", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Perfection", 604800);
call insert_carac_modifier ("Perfection", "Strength", 1);
call insert_carac_modifier ("Perfection", "Mental", 1);
call insert_carac_modifier ("Perfection", "Perception", 1);
call insert_carac_modifier ("Perfection", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Discretion", 604800);
call insert_carac_modifier ("Discretion", "Discretion", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Survival", 604800);
call insert_carac_modifier ("Survival", "Discretion", 1);
call insert_carac_modifier ("Survival", "Defense", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Caution", 604800);
call insert_carac_modifier ("Caution", "Discretion", 1);
call insert_carac_modifier ("Caution", "Resistance", 1);
INSERT INTO game_modifier  (name, health, duration)                    VALUES ("Health", 600, 0);
INSERT INTO game_modifier  (name, hydration, duration)                 VALUES ("ReHydrating", 1000, 0);
INSERT INTO game_modifier  (name, hydration, duration)                 VALUES ("ReHydration", 500, 0);
INSERT INTO game_modifier  (name, energy, duration)                    VALUES ("Combustion", 750, 0);
INSERT INTO game_modifier  (name, duration, speed)                     VALUES ("Efficiency", 172800, 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Sense", 604800);
call insert_carac_modifier ("Sense", "Perception", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Prescience", 604800);
call insert_carac_modifier ("Prescience", "Strength", 1);
call insert_carac_modifier ("Prescience", "Mental", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Authority", 604800);
call insert_carac_modifier ("Prescience", "Mental", 1);
call insert_carac_modifier ("Prescience", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Gentle", 604800);
call insert_carac_modifier ("Gentle", "Strength", 1);
call insert_carac_modifier ("Gentle", "Mental", 1);
call insert_carac_modifier ("Gentle", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Resistance", 604800);
call insert_carac_modifier ("Resistance", "Resistance", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Endurance", 604800);
call insert_carac_modifier ("Endurance", "Defense", 1);
call insert_carac_modifier ("Endurance", "Resistance", 1);
INSERT INTO game_modifier  (name, time, duration)                      VALUES ("Time", 50000, 0);
INSERT INTO game_modifier  (name, duration, speed)                     VALUES ("TimeImproved", 86400, 1);
INSERT INTO game_modifier  (name, energy, duration)                    VALUES ("Hungery", 500, 0);
INSERT INTO game_modifier  (name, energy, duration)                    VALUES ("HungeryImproved", 1000, 0);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Mental", 604800);
call insert_carac_modifier ("Mental", "Mental", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Charm", 604800);
call insert_carac_modifier ("Charm", "Strength", 1);
call insert_carac_modifier ("Charm", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Meditative", 604800);
call insert_carac_modifier ("Meditative", "Mental", 1);
call insert_carac_modifier ("Meditative", "Perception", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Intuition", 604800);
call insert_carac_modifier ("Intuition", "Perception", 1);
call insert_carac_modifier ("Intuition", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Capability", 604800);
call insert_carac_modifier ("Capability", "Strength", 1);
call insert_carac_modifier ("Capability", "Perception", 1);
call insert_carac_modifier ("Capability", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Psychic", 604800);
call insert_carac_modifier ("Psychic", "Mental", 1);
call insert_carac_modifier ("Psychic", "Perception", 1);
call insert_carac_modifier ("Psychic", "Charisma", 1);
INSERT INTO game_modifier  (name, duration)                            VALUES ("Seiza", 604800);
call insert_carac_modifier ("Seiza", "Discretion", 1);
call insert_carac_modifier ("Seiza", "Defense", 1);
call insert_carac_modifier ("Seiza", "Resistance", 1);

insert into game_ressource_modifier (item, modifier)
select
    i.id, m.id
from
    game_ressource_item as i,
    game_modifier as m
where
    i.name = concat("Balm", m.name) OR
    i.name = concat("Sirup", m.name) OR
    i.name = concat("Pill", m.name) ;

DROP PROCEDURE insert_carac_modifier;