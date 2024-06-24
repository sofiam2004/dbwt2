USE emensawerbeseite;

DELIMITER //

CREATE PROCEDURE inkrementiere_anmeldungen(IN benutzer_id INT)
BEGIN
    UPDATE emensawerbeseite.benutzer
    SET anzahlanmeldungen = anzahlanmeldungen + 1
    WHERE id = benutzer_id;
END //

DELIMITER ;
