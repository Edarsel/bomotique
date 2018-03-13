<?php

//require 'Modele/modele.php';

function getModeConnexion() {
    $bdd = getBdd();
    $stmt = $bdd->prepare('SELECT estEnModePassword FROM tbl_application');
    $stmt->execute();
    $objModeConnexion = $stmt->fetch(PDO::FETCH_ASSOC);
    return $objModeConnexion['estEnModePassword'];
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

function addLogconnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur) {
  $bdd = getBdd();
  $stmt = $bdd->prepare('INSERT INTO tbl_log_connexion'
  . '('
  . 'empreinteClient, '
  . 'dateHeure, '
  . 'connexionReussie, '
  . 'estAdministrateur, '
  . 'num_tbl_utilisateur '
  . ') '
  . 'VALUES '
  . '('
  . ':client,'
  . ':dateHeure,'
  . ':coReussie,'
  . ':estAdmin,'
  . ':numUtil'
  . ')');

  $stmt->bindParam(':client', $empreinteClient, PDO::PARAM_STR);
  $stmt->bindParam(':dateHeure', $dateHeure, PDO::PARAM_STR);
  $stmt->bindParam(':coReussie', $connexionReussie, PDO::PARAM_BOOL);
  $stmt->bindParam(':estAdmin', $estAdministrateur, PDO::PARAM_BOOL);
  $stmt->bindParam(':numUtil', $utilisateur, PDO::PARAM_INT);

  $stmt->execute();
  return $bdd->errorInfo();
}


function getInfoApplication() {
    $bdd = getBdd();
    $stmt = $bdd->prepare('SELECT * FROM tbl_application');
    $stmt->execute();
    $obj = $stmt->fetch(PDO::FETCH_OBJ);
    return $obj;
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


function getLogsConnexion() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT `tbl_log_connexion`.*, `tbl_utilisateur`.`nomUtilisateur`
FROM `tbl_log_connexion`
LEFT JOIN `tbl_utilisateur` ON `tbl_log_connexion`.`num_tbl_utilisateur` = `tbl_utilisateur`.`numero`
ORDER BY `dateHeure` DESC');
  $stmt->execute();
  $listeLogs = $stmt->fetchAll(PDO::FETCH_OBJ);
  return $listeLogs;
}

function uptParamSecurite($nbTentative,$tempsInterTenta,$tempsBlocage) {

  $bdd = getBdd();
  $stmt = $bdd->prepare('UPDATE `tbl_application` '
  . 'SET '
  . 'nbTentative = :nombreTentative, '
  . 'tempsBlocage = :tempsBlocage, '
  . 'tempsIntervaleTentative = :tempsIntervaleTentative '
  . 'WHERE '
  . 'numero = 1');

  $stmt->bindParam(':nombreTentative', $nbTentative, PDO::PARAM_INT);
  $stmt->bindParam(':tempsBlocage', $tempsBlocage, PDO::PARAM_STR);
  $stmt->bindParam(':tempsIntervaleTentative', $tempsInterTenta, PDO::PARAM_STR);

  if ($stmt->execute()) {
    echo "Les paramètres ont été enregistrés.";
  } else {
    echo "ERREUR : Les paramètres n'ont pas pu être enregistrés.";
  }

  return $bdd->errorInfo();
}
