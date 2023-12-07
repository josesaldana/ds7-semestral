DROP TABLE IF EXISTS invitaciones;
DROP TABLE IF EXISTS asistencias;

CREATE TABLE invitaciones (
 	numero BIGINT AUTO_INCREMENT PRIMARY KEY,
 	invitado VARCHAR(250) NOT NULL,
    acompanantes SMALLINT NOT NULL,
    mesa INT NOT NULL
);

CREATE TABLE asistencias (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    invitacion BIGINT NOT NULL,
    acompanantes SMALLINT NOT NULL,
    FOREIGN KEY (invitacion) REFERENCES invitaciones (numero)
);