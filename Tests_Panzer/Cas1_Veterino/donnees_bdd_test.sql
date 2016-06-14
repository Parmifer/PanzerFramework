USE `panzer_veterino_test`;

DELETE FROM `operer`;
DELETE FROM `diagnostiquer`;
DELETE FROM `prendre_en_charge`;
DELETE FROM `chat`;
DELETE FROM `infirmier`;
DELETE FROM `veterinaire`;
DELETE FROM `salle`;
DELETE FROM `ressources_humaines`;
DELETE FROM `user`;
DELETE FROM `role`;

INSERT INTO `role`
(`id`, `label`, `level`, `code`)
VALUES 
(1, 'Visiteur', 0, 'V'),
(2, 'Utilisateur', 1, 'U'),
(3, 'Administrateur', 2, 'A'),
(4, 'Super administrateur', 3, 'S');

INSERT INTO `user`
(`id`, `pseudo`, `password`, `creation_date`, `role_id`)
VALUES 
(1, 'josiane_debret', SHA2('aze', 512), NOW(), 1), -- done
(2, 'michel_djida', SHA2('qsd', 512), NOW(), 1),-- done
(5, 'elise_badinent', SHA2('wxc', 512), NOW(), 1),-- done
(7, 'pauline_marchant', SHA2('rty', 512), NOW(), 1),
(8, 'bastien_ziaten', SHA2('fgh', 512), NOW(), 1),-- done
(3, 'maurice_ponnet', SHA2('vbn', 512), NOW(), 2),-- done
(4, 'marceline_mercier', SHA2('uio', 512), NOW(), 2),-- done
(6, 'mohamed_benzine', SHA2('jkl', 512), NOW(), 3);

INSERT INTO `ressources_humaines`
(`id`, `prenom`, `nom`, `adresse`, `salaire`, `user_id`)
VALUES 
(1, 'Maurice', 'Ponnet', '221 avenue des droits de lhomme 34000 MONTPELLIER', 1650, 3),
(2, 'Marceline', 'Mercier', '499 rue de Bouillargues 30000 NIMES', 1655, 4);

INSERT INTO `veterinaire`
(`id`, `prenom`, `nom`, `adresse`, `salaire`, `user_id`)
VALUES 
(1, 'Josiane', 'Debret', '394 RN7 la plaine 38500 AUBERIVES', 1850, 1),
(2, 'Elise', 'Badinent', '1000 rue des Concombre Masqués 68950 Tsoin-Tsoin-Les-Tartignoles', 1700, 5),
(3, 'Bastien', 'Ziaten', '15 impasse du Parc 79000 NIORT', 1900, 8);

INSERT INTO `infirmier`
(`id`, `prenom`, `nom`, `adresse`, `salaire`, `user_id`)
VALUES 
(1, 'Michel', 'Djida', '135 boulevard Gambetta 87000 LIMOGES', 1750, 1),
(2, 'Pauline', 'Marchant', '8 avenue du Levant 81600 GAILLAC', 1600, 1);

INSERT INTO `salle`
(`id`, `etage`, `numero`, `nombre_places`)
VALUES 
(1, 0, '500', 10),
(2, 0, '600', 5),
(3, 1, 'E6', 3),
(4, 1, '038', 8),
(5, 1, '100', 5);

INSERT INTO `chat`
(`id`, `nom`, `adresse`, `reference`, `etat`, `salle_id`)
VALUES 
(1, 'Moustache', '9 rue Doudart De Lagrée 38000 GRENOBLE', 'CHA-001-001', 'ok', 1),
(2, 'Pelotte', '137 rue Marc Seguin 30000 NIMES', 'CHA-001-002', 'entré', null),
(3, 'Grumpy', '152 avenue du Levant 87000 LIMOGES', 'CHA-001-003', 'pris en charge', 1),
(4, 'Washin-Tonne', '394 RN7 la plaine 79000 NIORT', 'CHA-001-004', 'rétablissement', 2);

INSERT INTO `prendre_en_charge`
(`chat_id`, `infirmier_id`)
VALUES 
(1, 2),
(2, 1),
(3, 1),
(4, 2);

INSERT INTO `diagnostiquer`
(`chat_id`, `veterinaire_id`, `date`, `diagnostic`)
VALUES 
(1, 1, NOW(), 'Petite toux bégnine. MàJ : Peut sortir après prescription des médicaments.'),
(3, 2, NOW(), 'Semble désorienté. A été retrouvé après une chutte du troisième étage. Examen complémentaire par le Docteur Ziaten.'),
(4, 3, NOW(), 'Miaule de douleur depuis plusieurs jours. Après examen, constate petite masse. MàJ : IRM révèle une tumeur. MàJ : opération programmée. MàJ : opération effectuée sans complications.');

INSERT INTO `operer`
(`chat_id`, `veterinaire_id`, `date`)
VALUES
(4, 3, NOW());
