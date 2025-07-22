DROP TABLE IF EXISTS Ecrivains;
CREATE TABLE Ecrivains (
    idEcrivain int AUTO_INCREMENT NOT NULL,
    nomEcrivain VARCHAR(256),
    prenomEcrivain VARCHAR(256),
    nationalitéEcrivain VARCHAR(256),
    PRIMARY KEY (idEcrivain)
) ENGINE = InnoDB;
DROP TABLE IF EXISTS Livres;
CREATE TABLE Livres (
    idLivre int AUTO_INCREMENT NOT NULL,
    nomLivre VARCHAR(256),
    annéeLivre INT,
    disponible BOOL,
    idEcrivain INT,
    idGenre INT,
    PRIMARY KEY (idLivre)
) ENGINE = InnoDB;
DROP TABLE IF EXISTS Genres;
CREATE TABLE Genres (
    idGenre int AUTO_INCREMENT NOT NULL,
    libelle VARCHAR(256),
    PRIMARY KEY (idGenre)
) ENGINE = InnoDB;
DROP TABLE IF EXISTS Emprunts;
CREATE TABLE Emprunts (
    idEmprunt int AUTO_INCREMENT NOT NULL,
    dateEmprunt DATE,
    dateRetour DATE,
    idLivre INT NOT NULL,
    idUtilisateur INT NOT NULL,
    PRIMARY KEY (idEmprunt)
) ENGINE = InnoDB;
DROP TABLE IF EXISTS Utilisateurs;
CREATE TABLE Utilisateurs (
    idUtilisateur int AUTO_INCREMENT NOT NULL,
    nomUtilisateur VARCHAR(256),
    prenomUtilisateur VARCHAR(256),
    emailUtilisateur VARCHAR(256),
    PRIMARY KEY (idUtilisateur)
) ENGINE = InnoDB;
ALTER TABLE Livres
ADD CONSTRAINT FK_Livres_idEcrivain FOREIGN KEY (idEcrivain) REFERENCES Ecrivains (idEcrivain);
ALTER TABLE Livres
ADD CONSTRAINT FK_Livres_idGenre FOREIGN KEY (idGenre) REFERENCES Genres (idGenre);
ALTER TABLE Emprunts
ADD CONSTRAINT FK_Emprunts_idLivre FOREIGN KEY (idLivre) REFERENCES Livres (idLivre);
ALTER TABLE Emprunts
ADD CONSTRAINT FK_Emprunts_idUtilisateur FOREIGN KEY (idUtilisateur) REFERENCES Utilisateurs (idUtilisateur);