
# Fonction de Modification des Bases (graisseuses, sucrees...)

CREATE PROCEDURE update_basis_in (skill_name VARCHAR(64), item_name VARCHAR(64), quantity DOUBLE)
BEGIN
    DECLARE skill_id, item_id INT;
    SET skill_id = (SELECT id from game_skill_skill where name=skill_name);
    SET item_id  = (SELECT id from game_ressource_item where name=item_name);
    UPDATE game_skill_in SET amount=quantity where item=item_id AND skill=skill_id;
END ;

# Fonction de Modification des entrees d une potion

CREATE PROCEDURE update_alchimy_in (potion_name VARCHAR(64), item_name VARCHAR(64), quantity DOUBLE)
BEGIN
    DECLARE Skill_id, potion_id, item_id INT;
    SET potion_id = (SELECT id from game_ressource_item where name=potion_name);
    SET item_id   = (SELECT id from game_ressource_item where name=item_name);
    SET Skill_id  = (SELECT skill from game_skill_out where item=potion_id);
    UPDATE game_skill_in SET amount=quantity where item=item_id AND skill=Skill_id;
END ;

# Fonction de Modification du nombre de potions en sortie

CREATE PROCEDURE update_alchimy_out (potion_name VARCHAR(64), quantity DOUBLE)
BEGIN
    DECLARE potion_id INT;
    SET potion_id = (SELECT id from game_ressource_item where name=potion_name);
    UPDATE game_skill_out SET amount=quantity where item=`potion_id` ;
END ;

# Modification des bases graisseuses.

call update_basis_in("MakeFatBasisOli", "OliButter", 1.5);
call update_basis_in("MakeFatBasisArido", "AridoButter", 1.5);
call update_basis_in("MakeFatBasisAloe", "AloeGel", 0.5);
call update_basis_in("MakeFatBasisThorno", "ThornoButter", 1.5);

# Modification des bases sucrées

call update_basis_in("MakeSugarBasisSugar", "Sugar", 0.5);
call update_basis_in("MakeSugarBasisBero", "BeroJuice", 4);
call update_basis_in("MakeSugarBasisBao", "BaoJuice", 4);
call update_basis_in("MakeSugarBasisAvoro", "AvoroJuice", 4);
call update_basis_in("MakeSugarBasisThorno", "ThornoJuice", 4);
call update_basis_in("MakeSugarBasisKakto", "KaktoJuice", 4);

# Modification des bases pilules

call update_basis_in("MakePillBasisSugar", "Sugar", 0.5);

# Mise à jour des potions

call update_alchimy_in("BalmRecovery", "BasketeryPot", 7);
call update_alchimy_out("BalmRecovery", 7);
call update_alchimy_in("BalmAura", "EikoPlant", 3);
call update_alchimy_in("BalmAura", "LavoPlant", 4);
call update_alchimy_in("BalmPhysic", "EikoPlant", 2);
call update_alchimy_in("BalmCharisma", "EikoPlant", 2);
call update_alchimy_in("BalmNatural", "AbiFruit", 2);
call update_alchimy_in("BalmNatural", "FlentoFlowaer", 3);
call update_alchimy_in("BalmNatural", "LigioJuice", 5);
call update_alchimy_in("BalmNatural", "LichojPlant", 2);
call update_alchimy_in("BalmIntellection", "AloeJuice", 5);
call update_alchimy_in("BalmIntellection", "PinFruit", 4);
call update_alchimy_in("BalmIntellection", "LigioJuice", 4);
call update_alchimy_in("BalmIntellection", "EikoPlant", 4);
call update_alchimy_in("BalmIntellection", "BailoPlant", 5);
call update_alchimy_in("BalmPerfection", "AloeJuice", 5);
call update_alchimy_in("BalmPerfection", "PinFruit", 4);
call update_alchimy_in("BalmPerfection", "FlentoFlower", 4);
call update_alchimy_in("BalmPerfection", "ThornoFruit", 4);
call update_alchimy_in("BalmPerfection", "BailoPlant", 4);
call update_alchimy_in("BalmPerfection", "LichojPlant", 5);
call update_alchimy_in("BalmDiscretion", "LichojPlant", 2);
call update_alchimy_in("BalmSurvival", "AloeJuice", 4);
call update_alchimy_in("BalmSurvival", "FlentoFlower", 4);
call update_alchimy_in("BalmSurvival", "LigioJuice", 4);
call update_alchimy_in("BalmSurvival", "LavoPlant", 5);
call update_alchimy_in("BalmCaution", "AbiFruit", 3);
call update_alchimy_in("BalmCaution", "EikoPlant", 3);
call update_alchimy_in("BalmCaution", "ThornoFruit", 3);
call update_alchimy_in("BalmCaution", "BailoPlant", 2);
call update_alchimy_in("SirupReHydrating", "Flask", 7);
call update_alchimy_out("SirupReHydrating", 7);
call update_alchimy_in("SirupReHydration", "Flask", 20);
call update_alchimy_out("SirupReHydration", 20);
call update_alchimy_in("SirupCombustion", "PinFruit", 2);
call update_alchimy_in("SirupCombustion", "ThornoFruit",2);
call update_alchimy_in("SirupEfficiency", "LigioJuice", 25);
call update_alchimy_in("SirupEfficiency", "BailoPlant", 8);
call update_alchimy_in("SirupSense", "LigioJuice", 4);
call update_alchimy_in("SirupSense", "BailoPlant", 2);
call update_alchimy_in("SirupPrescience", "AloeJuice", 4);
call update_alchimy_in("SirupPrescience", "AbiFruit", 3);
call update_alchimy_in("SirupPrescience", "PinFruit", 3);
call update_alchimy_in("SirupPrescience", "LavoPlant", 4);
call update_alchimy_in("SirupAuthority", "AloeJuice", 4);
call update_alchimy_in("SirupAuthority", "PinFruit", 3);
call update_alchimy_in("SirupAuthority", "FlentoFlower", 3);
call update_alchimy_in("SirupAuthority", "EikoPlant", 4);
call update_alchimy_in("SirupGentle", "AloeJuice", 4);
call update_alchimy_in("SirupGentle", "PinFruit", 4);
call update_alchimy_in("SirupGentle", "LavoPlant", 4);
call update_alchimy_in("SirupGentle", "ThornoFruit", 4);
call update_alchimy_in("SirupGentle", "LichojPlant", 5);
call update_alchimy_in("SirupResistance", "FlentoFlower", 2);
call update_alchimy_in("SirupEndurance", "AloeJuice", 4);
call update_alchimy_in("SirupEndurance", "PinFruit", 4);
call update_alchimy_in("SirupEndurance", "BailoPlant", 3);
call update_alchimy_in("SirupEndurance", "LichojPlant", 3);
call update_alchimy_in("PillTimeImproved", "ThornoFruit", 4);
call update_alchimy_in("PillTimeImproved", "LigioJuice", 8);
call update_alchimy_in("PillHungery", "FlentoFlower", 1);
call update_alchimy_in("PillHungery", "LichojPlant", 1);
call update_alchimy_in("PillHungeryImproved", "AloeJuice", 5);
call update_alchimy_in("PillHungeryImproved", "FlentoFlower", 3);
call update_alchimy_in("PillHungeryImproved", "LichojPlant", 3);
call update_alchimy_in("PillMental", "AloeJuice", 5);
call update_alchimy_in("PillMental", "LigioJuice", 4);
call update_alchimy_in("PillMental", "BailoPlant", 2);
call update_alchimy_in("PillCharm", "AloeJuice", 5);
call update_alchimy_in("PillCharm", "LavoPlant", 3);
call update_alchimy_in("PillCharm", "EikoPlant", 3);
call update_alchimy_in("PillCharm", "BailoPlant", 4);
call update_alchimy_in("PillMeditative", "AbiFruit", 3);
call update_alchimy_in("PillMeditative", "PinFruit", 3);
call update_alchimy_in("PillMeditative", "EikoPlant", 3);
call update_alchimy_in("PillMeditative", "BailoPlant", 2);
call update_alchimy_in("PillIntuition", "AbiFruit", 3);
call update_alchimy_in("PillIntuition", "LavoPlant", 3);
call update_alchimy_in("PillIntuition", "ThornoFruit", 3);
call update_alchimy_in("PillIntuition", "LichojPlant", 2);
call update_alchimy_in("PillCapability", "AbiFruit", 4);
call update_alchimy_in("PillCapability", "FlentoFlower", 4);
call update_alchimy_in("PillCapability", "LigioJuice", 5);
call update_alchimy_in("PillCapability", "BailoPlant", 4);
call update_alchimy_in("PillCapability", "LichojPlant", 5);
call update_alchimy_in("PillPsychic", "AbiFruit", 4);
call update_alchimy_in("PillPsychic", "LigioJuice", 5);
call update_alchimy_in("PillPsychic", "LavoPlant", 4);
call update_alchimy_in("PillPsychic", "EikoPlant", 4);
call update_alchimy_in("PillPsychic", "ThornoFruit", 5);
call update_alchimy_in("PillSeiza", "PinFruit", 4);
call update_alchimy_in("PillSeiza", "FlentoFlower", 4);
call update_alchimy_in("PillSeiza", "LigioJuice", 9);
call update_alchimy_in("PillSeiza", "LavoPlant", 4);
call update_alchimy_in("PillSeiza", "EikoPlant", 4);

DROP PROCEDURE update_alchimy_in;
DROP PROCEDURE update_alchimy_out;
DROP PROCEDURE update_basis_in;