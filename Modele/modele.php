<?php

//Les fonctions commencent par le mot anglais par soucis de simplicité
// Effectue la connexion à la BDD
// Instancie et renvoie l'objet PDO associé
function getBdd() {
  $bdd;

  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', '');
  } else {
    $bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'phpmyadmin', 'pass1234');
  }

  //$bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'phpmyadmin', 'pass1234');
  return $bdd;
}

function initDB($strMdp){
  $bdd = getBdd();

  $stmt = $bdd->prepare('CREATE TABLE `tbl_application` (
    `numero` int(11) NOT NULL,
    `estEnModePassword` tinyint(1) NOT NULL,
    `motDePasse` varchar(255) DEFAULT NULL,
    `tempsImpulsion` int(11) NOT NULL DEFAULT \'1000\'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  --
  -- Contenu de la table `tbl_application`
  --

  INSERT INTO `tbl_application` (`numero`, `estEnModePassword`, `motDePasse`) VALUES (1, 1, :mdp);

  -- --------------------------------------------------------

  --
  -- Structure de la table `tbl_utilisateur`
  --

  CREATE TABLE `tbl_utilisateurs` (
    `numero` int(11) NOT NULL,
    `nomUtilisateur` varchar(255) NOT NULL,
    `motDePasse` varchar(255) NOT NULL,
    `estAdministrateur` tinyint(1) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  --
  -- Index pour les tables exportées
  --
  INSERT INTO `tbl_utilisateurs`(`numero`,`nomUtilisateur`, `motDePasse`, `estAdministrateur`) VALUES (1,"admin",:mdp2,1);
  --
  -- Index pour la table `tbl_application`
  --
  ALTER TABLE `tbl_application`
  ADD PRIMARY KEY (`numero`);

  --
  -- Index pour la table `tbl_utilisateur`
  --
  ALTER TABLE `tbl_utilisateurs`
  ADD PRIMARY KEY (`numero`),
  ADD UNIQUE KEY `nomUtilisateur` (`nomUtilisateur`);

  --
  -- AUTO_INCREMENT pour les tables exportées
  --

  --
  -- AUTO_INCREMENT pour la table `tbl_application`
  --
  ALTER TABLE `tbl_application`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  --
  -- AUTO_INCREMENT pour la table `tbl_utilisateur`
  --
  ALTER TABLE `tbl_utilisateurs`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;');
  $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
  $stmt->bindParam(':mdp2', $strMdp, PDO::PARAM_STR);
  return $stmt->execute();
}

function getUtilisateur($strNomUtil) {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM `tbl_utilisateurs` WHERE nomUtilisateur = :nomUtil');
  $stmt->bindParam(':nomUtil', $strNomUtil, PDO::PARAM_STR);
  $stmt->execute();
  $objUtilisateur = $stmt->fetch(PDO::FETCH_OBJ);
  return $objUtilisateur;
}

function getUtilisateurs() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM `tbl_utilisateurs`');
  $stmt->execute();
  $objListeUtil = $stmt->fetchAll(PDO::FETCH_OBJ);
  return $objListeUtil;
}

function addUtilisateur($strNomUtil, $iAdmin, $strMdp) {

  $bdd = getBdd();
  $stmt = $bdd->prepare('INSERT INTO tbl_utilisateurs'
  . '('
  . 'nomUtilisateur, '
  . 'motDePasse, '
  . 'estAdministrateur '
  . ') '
  . 'VALUES '
  . '('
  . ':nomUtil,'
  . ':mdp,'
  . ':estAdmin'
  . ')');

  $stmt->bindParam(':nomUtil', $strNomUtil, PDO::PARAM_STR);
  $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
  $stmt->bindParam(':estAdmin', $iAdmin, PDO::PARAM_BOOL);

  if ($stmt->execute()) {
    echo "L'utlisateur \"" . $strNomUtil . "\" a été ajouté.";
  } else {
    echo "ERREUR : L'utilisateur \"" . $strNomUtil . "\" n'a pas pu être ajouté dans la base de données.";
  }

  return $bdd->errorInfo();
}


function updtUtilisateur($iIDUtil, $strNomUtil, $iAdmin, $strMdp) {

  $bdd = getBdd();
  $stmt;

  if ($strMdp) {

    $stmt = $bdd->prepare('UPDATE `tbl_utilisateurs` '
    . 'SET '
    . 'nomUtilisateur = :nomUtil,'
    . 'motDePasse =:mdp,'
    . 'estAdministrateur =:estAdmin '
    . 'WHERE '
    . 'numero = :idUtil');

    $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);

  } else {
    $stmt = $bdd->prepare('UPDATE tbl_utilisateurs '
    . 'SET '
    . 'nomUtilisateur = :nomUtil,'
    . 'estAdministrateur = :estAdmin '
    . 'WHERE '
    . 'numero = :idUtil');
  }

  $stmt->bindParam(':nomUtil', $strNomUtil, PDO::PARAM_STR);
  $stmt->bindParam(':estAdmin', $iAdmin, PDO::PARAM_BOOL);
  $stmt->bindParam(':idUtil', $iIDUtil, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "L'utilisateur \"" . $strNomUtil . "\" a été modifié.";
  } else {
    echo "ERREUR : L'utilisateur \"" . $strNomUtil . "     ". $iIDUtil .  "     ". $strMdp ."\" n'a pas pu être modifié dans la base de données.";
  }

  return $bdd->errorInfo();
}

function delUtilisateur($iIDUtil) {

  $bdd = getBdd();
  $stmt = $bdd->prepare('DELETE FROM `tbl_utilisateurs` WHERE numero = :idUtil');

  $stmt->bindParam(':idUtil', $iIDUtil, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "L'utilisateur a été supprimé.";
  } else {
    echo "ERREUR : L'utilisateur n'a pas pu être supprimé de la base de données.";
  }

  return $bdd->errorInfo();
}


function uptModeConnexion($iModeCo, $strMdp) {

  $bdd = getBdd();
  $stmt;

  if ($iModeCo ==1) {

    $stmt = $bdd->prepare('UPDATE `tbl_application` '
    . 'SET '
    . 'estEnModePassword = :modePass,'
    . 'motDePasse =:mdp '
    . 'WHERE '
    . 'numero = 1');

    $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);

  } else {
    $stmt = $bdd->prepare('UPDATE tbl_application '
    . 'SET '
    . 'estEnModePassword = :modePass '
    . 'WHERE '
    . 'numero = 1');
  }

  $stmt->bindParam(':modePass', $iModeCo, PDO::PARAM_BOOL);

  if ($stmt->execute()) {
    echo "Le mode de connexion a été changé.";
  } else {
    echo "ERREUR : Le mode de connexion n'a pas pu être changé.";
  }

  return $bdd->errorInfo();
}

function uptTempsImpulsion($iTempsImpuls) {
  $bdd = getBdd();
  $stmt;

  $stmt = $bdd->prepare('UPDATE tbl_application '
  . 'SET '
  . 'tempsImpulsion = :tempsImpuls '
  . 'WHERE '
  . 'numero = 1');
  $stmt->bindParam(':tempsImpuls', $iTempsImpuls, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "Le temps d'impulsion a été changé à ".$iTempsImpuls." milliseconde(s)";
  } else {
    echo "ERREUR : Le temps d'impulsion n'a pas pu être changé.";
  }

  return $bdd->errorInfo();
}

function getTempsImpulsion() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT tempsImpulsion FROM `tbl_application` WHERE numero = 1');
  $stmt->execute();
  $tempsImpuls = $stmt->fetch(PDO::FETCH_OBJ);
  return $tempsImpuls;
}

//==============================

function getUtilisateurMDP($strNomUtil) {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM `tbl_eleves`,`tbl_classes`,`tbl_localites` WHERE tbl_eleves.nomUtilisateur = :nomUtil AND tbl_eleves.num_tbl_classes = tbl_classes.numero AND tbl_eleves.num_tbl_localites = tbl_localites.numero');
  $stmt->bindParam(':nomUtil', $strNomUtil, PDO::PARAM_STR);
  $stmt->execute();
  $objUtilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
  return $objUtilisateur;
}

function getElevesParClasse($strNomClasse) {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT tbl_eleves.numero, nomEleve, prenomEleve, adresseRue, adresseNumero, npaLocalite, nomLocalite FROM `tbl_eleves`,tbl_classes,tbl_localites WHERE tbl_classes.libelleClasse = :nomClasse AND tbl_eleves.num_tbl_classes = tbl_classes.numero AND tbl_eleves.num_tbl_localites = tbl_localites.numero');
  $stmt->bindParam(':nomClasse', $strNomClasse, PDO::PARAM_STR);
  $stmt->execute();
  $objListeEleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $objListeEleves;
}

function getEleves() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT *,tbl_eleves.numero AS numero_eleve,tbl_classes.numero AS numero_classe,tbl_localites.numero AS numero_localite FROM `tbl_eleves`,tbl_classes,tbl_localites WHERE tbl_eleves.num_tbl_classes = tbl_classes.numero AND tbl_eleves.num_tbl_localites = tbl_localites.numero');
  $stmt->execute();
  $objListeEleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $objListeEleves;
}

function getClasses() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM tbl_classes ORDER BY libelleClasse');
  $stmt->execute();
  $objListeClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $objListeClasses;
}

function getLocalites() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM tbl_localites ORDER BY nomLocalite');
  $stmt->execute();
  $objListeLocalites = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $objListeLocalites;
}

function getLocaliteRandom() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM tbl_localites ORDER BY RAND() LIMIT 1');
  $stmt->execute();
  $objListeLocalites = $stmt->fetch(PDO::FETCH_ASSOC);
  return $objListeLocalites;
}

function addEleve($strNom, $strPrenom, $iClasse, $strRue, $strNumRue, $iLocal, $strUser, $iAdmin, $strMdp) {

  $bdd = getBdd();
  $stmt;
  if (is_null($strUser)) {
    $stmt = $bdd->prepare('INSERT INTO tbl_eleves'
    . '('
    . 'prenomEleve, '
    . 'nomEleve, '
    . 'adresseRue, '
    . 'adresseNumero, '
    . 'estAdministrateur, '
    . 'num_tbl_localites, '
    . 'num_tbl_classes'
    . ') '
    . 'VALUES '
    . '('
    . ':prenomE,'
    . ':nomE,'
    . ':adresse,'
    . ':numRue,'
    . ':estAdmin,'
    . ':numLocal,'
    . ':numClasse'
    . ')');
  } else {
    $stmt = $bdd->prepare('INSERT INTO tbl_eleves'
    . '('
    . 'prenomEleve, '
    . 'nomEleve, '
    . 'adresseRue, '
    . 'adresseNumero, '
    . 'nomUtilisateur, '
    . 'motDePasse, '
    . 'estAdministrateur, '
    . 'num_tbl_localites, '
    . 'num_tbl_classes'
    . ') '
    . 'VALUES '
    . '('
    . ':prenomE,'
    . ':nomE,'
    . ':adresse,'
    . ':numRue,'
    . ':nomUser,'
    . ':mdp,'
    . ':estAdmin,'
    . ':numLocal,'
    . ':numClasse'
    . ')');

    $stmt->bindParam(':nomUser', $strUser, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
  }

  $stmt->bindParam(':prenomE', $strPrenom, PDO::PARAM_STR);
  $stmt->bindParam(':nomE', $strNom, PDO::PARAM_STR);
  $stmt->bindParam(':adresse', $strRue, PDO::PARAM_STR);
  $stmt->bindParam(':numRue', $strNumRue, PDO::PARAM_STR);
  $stmt->bindParam(':estAdmin', $iAdmin, PDO::PARAM_BOOL);
  $stmt->bindParam(':numLocal', $iLocal, PDO::PARAM_INT);
  $stmt->bindParam(':numClasse', $iClasse, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "L'élève \"" . $strPrenom . " " . $strNom . "\" a été ajouté.";
  } else {
    echo "ERREUR : L'élève \"" . $strPrenom . " " . $strNom . "\" n'a pas pu être ajouté dans la base de données.";
  }

  return $bdd->errorInfo();
}

function updtEleve($iIDEleve, $strNom, $strPrenom, $iClasse, $strRue, $strNumRue, $iLocal, $strUser, $iAdmin, $strMdp) {

  $bdd = getBdd();
  $stmt;

  if ($strUser != "" && $strMdp == "") {
    $stmt = $bdd->prepare('UPDATE `tbl_eleves` '
    . 'SET '
    . '`prenomEleve`=:prenomE,'
    . '`nomEleve`=:nomE,'
    . '`adresseRue`=:adresse,'
    . '`adresseNumero`=:numRue,'
    . '`nomUtilisateur`=:nomUser,'
    . '`estAdministrateur`=:estAdmin,'
    . '`num_tbl_localites`=:numLocal,'
    . '`num_tbl_classes`=:numClasse '
    . 'WHERE '
    . 'numero = :idEleve');
  } else {
    $stmt = $bdd->prepare('UPDATE `tbl_eleves` '
    . 'SET '
    . '`prenomEleve`=:prenomE,'
    . '`nomEleve`=:nomE,'
    . '`adresseRue`=:adresse,'
    . '`adresseNumero`=:numRue,'
    . '`nomUtilisateur`=:nomUser,'
    . '`motDePasse`=:mdp,'
    . '`estAdministrateur`=:estAdmin,'
    . '`num_tbl_localites`=:numLocal,'
    . '`num_tbl_classes`=:numClasse '
    . 'WHERE '
    . 'numero = :idEleve');

    $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
  }

  $stmt->bindParam(':nomUser', $strUser, PDO::PARAM_STR);
  $stmt->bindParam(':prenomE', $strPrenom, PDO::PARAM_STR);
  $stmt->bindParam(':nomE', $strNom, PDO::PARAM_STR);
  $stmt->bindParam(':adresse', $strRue, PDO::PARAM_STR);
  $stmt->bindParam(':numRue', $strNumRue, PDO::PARAM_STR);
  $stmt->bindParam(':estAdmin', $iAdmin, PDO::PARAM_BOOL);
  $stmt->bindParam(':numLocal', $iLocal, PDO::PARAM_INT);
  $stmt->bindParam(':numClasse', $iClasse, PDO::PARAM_INT);
  $stmt->bindParam(':idEleve', $iIDEleve, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "L'élève \"" . $strPrenom . " " . $strNom . "\" a été modifié.";
  } else {
    echo "ERREUR : L'élève \"" . $strPrenom . " " . $strNom . "\" n'a pas pu être modifié dans la base de données.";
  }

  return $bdd->errorInfo();
}

function delEleve($iIDEleve) {

  $bdd = getBdd();
  $stmt = $bdd->prepare('DELETE FROM `tbl_eleves` WHERE numero = :idEleve');

  $stmt->bindParam(':idEleve', $iIDEleve, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "L'élève a été supprimé.";
  } else {
    echo "ERREUR : L'élève n'a pas pu être supprimé dans la base de données.";
  }

  return $bdd->errorInfo();
}
