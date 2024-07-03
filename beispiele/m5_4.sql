-- a) Suppengerichte
CREATE VIEW view_suppengerichte AS
SELECT *
FROM gericht
WHERE name LIKE '%suppe%';

-- b) Anmeldungen
CREATE VIEW view_anmeldungen AS
SELECT benutzer_id, COUNT(*) AS anzahl_anmeldungen
FROM anmeldungen
GROUP BY benutzer_id
ORDER BY anzahl_anmeldungen DESC;

-- c) Vegetarische Gerichte pro Kategorie
CREATE VIEW view_kategoriegerichte_vegetarisch AS
SELECT k.id AS kategorie_id, k.name AS kategorie_name, g.id AS gericht_id, g.name AS gericht_name
FROM kategorie k
         LEFT JOIN gericht g ON k.id = g.kategorie_id AND g.vegetarisch = 1
ORDER BY k.name, g.name