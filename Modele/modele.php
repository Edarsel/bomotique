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
