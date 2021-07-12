CREATE DATABASE <Datenbank>
CHARACTER SET 'UTF8';
use <Datenbank>;
GRANT ALL PRIVILEGES ON <Datenbank>.* TO '<Nutzername>'@'<Adresse-des-Datenbanken-Servers>' IDENTIFIED BY '<Passwort>';
CREATE TABLE personen (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nachname VARCHAR(255) NOT NULL,
    vorname VARCHAR(255) NOT NULL
);

ALTER TABLE personen AUTO_INCREMENT = 10000;

CREATE TABLE nummern (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    type ENUM('Festnetz', 'Mobilfunk', 'Fax'),
    zweck ENUM('Privat', 'Geschäftlich'),
    intl_vorwahl CHAR(3),
    vorwahl VARCHAR(255) NOT NULL,
    nummer VARCHAR(255) NOT NULL,
    person_id INT NOT NULL,
    FOREIGN KEY (person_id) REFERENCES personen(id)
);

CREATE TABLE email_adressen (
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    zweck ENUM('Privat', 'Geschäftlich'),
    email VARCHAR(255) NOT NULL,
    person_id INT NOT NULL,
    FOREIGN KEY (person_id) REFERENCES personen(id)
);

CREATE TABLE adressen (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    zweck ENUM('Privat', 'Geschäftlich'),
    straße VARCHAR(255),
    hausnr VARCHAR(255),
    plz MEDIUMINT(255) UNSIGNED,
    stadt VARCHAR(255),
    land VARCHAR(255),
    person_id INT NOT NULL,
    FOREIGN KEY (person_id) REFERENCES personen(id)
);
