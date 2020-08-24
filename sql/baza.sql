CREATE DATABASE todo;

CREATE TABLE uporabniki (
    ID int NOT NULL AUTO_INCREMENT,
    ime varchar(25) NOT NULL,
    enaslov varchar(25),
    geslo varchar(25),
    PRIMARY KEY (ID)
)CHARACTER SET utf8 COLLATE utf8_slovenian_ci;

CREATE TABLE opravila (
    ID int NOT NULL AUTO_INCREMENT,
    uporabnikID int,
    naslov varchar(25) NOT NULL,
    projekt varchar(25),
    vsebina varchar(100),
    ustvarjeno DATE,
    status int(3),
    opravljeno DATETIME,
    PRIMARY KEY (ID),
    FOREIGN KEY (uporabnikID) REFERENCES uporabniki(ID)
)CHARACTER SET utf8 COLLATE utf8_slovenian_ci;