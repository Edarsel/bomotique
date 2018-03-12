#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: UTILISATEUR
#------------------------------------------------------------

CREATE TABLE tbl_utilisateur(
        numero            int (11) Auto_increment  NOT NULL ,
        nomUtilisateur    Varchar (25) NOT NULL ,
        motDePasse        Varchar (255) NOT NULL ,
        estAdministrateur Bool NOT NULL ,
        PRIMARY KEY (numero ) ,
        UNIQUE (nomUtilisateur )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: APPLICATION
#------------------------------------------------------------

CREATE TABLE tbl_application(
        numero                  int (11) Auto_increment  NOT NULL ,
        estEnModePassword       Bool NOT NULL ,
        motDePasse              Varchar (255) ,
        tempsImpulsion          Int NOT NULL ,
        nbTentative             Int NOT NULL ,
        tempsBlocage            Time NOT NULL ,
        tempsIntervaleTentative Time NOT NULL ,
        PRIMARY KEY (numero )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: LOG_CONNEXION
#------------------------------------------------------------

CREATE TABLE tbl_log_connexion(
        numero             int (11) Auto_increment  NOT NULL ,
        empreinteClient    Varchar (255) NOT NULL ,
        dateHeure          Datetime NOT NULL ,
        connexionReussie   Bool NOT NULL ,
        estAdministrateur  Bool NOT NULL ,
        num_tbl_utilisateur Int ,
        PRIMARY KEY (numero )
)ENGINE=InnoDB;

ALTER TABLE tbl_log_connexion ADD CONSTRAINT FK_LOG_CONNEXION_numero_UTILISATEUR FOREIGN KEY (num_tbl_utilisateur) REFERENCES tbl_utilisateur(numero);

INSERT INTO tbl_utilisateur (numero,nomUtilisateur, motDePasse, estAdministrateur) VALUES (1,"admin", :mdp2 ,1);
  INSERT INTO tbl_application (numero, estEnModePassword, motDePasse,tempsImpulsion,nbTentative,tempsBlocage,tempsIntervaleTentative) VALUES (1, 1, :mdp, 5000, 5, '00:30:00', '00:00:02');
