<?php

//require 'Modele/modele.php';

function getModeConnexion() {
    $bdd = getBdd();
    $stmt = $bdd->prepare('SELECT estEnModePassword FROM tbl_application');
    $stmt->execute();
    $objModeConnexion = $stmt->fetch(PDO::FETCH_ASSOC);
    return $objModeConnexion['estEnModePassword'];
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
