<?php

function getUtilisateurParNom($strNomUtil) {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM `tbl_utilisateur` WHERE nomUtilisateur = :nomUtil');
  $stmt->bindParam(':nomUtil', $strNomUtil, PDO::PARAM_STR);
  $stmt->execute();
  $objUtilisateur = $stmt->fetch(PDO::FETCH_OBJ);
  return $objUtilisateur;
}

function getUtilisateurParID($idUtil) {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM `tbl_utilisateur` WHERE numero = :idUtil');
  $stmt->bindParam(':idUtil', $idUtil, PDO::PARAM_INT);
  $stmt->execute();
  $objUtilisateur = $stmt->fetch(PDO::FETCH_OBJ);
  return $objUtilisateur;
}

function getUtilisateurs() {
  $bdd = getBdd();
  $stmt = $bdd->prepare('SELECT * FROM `tbl_utilisateur`');
  $stmt->execute();
  $objListeUtil = $stmt->fetchAll(PDO::FETCH_OBJ);
  return $objListeUtil;
}

function addUtilisateur($strNomUtil, $iAdmin, $strMdp) {

  $bdd = getBdd();
  $stmt = $bdd->prepare('INSERT INTO tbl_utilisateur'
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

    $stmt = $bdd->prepare('UPDATE `tbl_utilisateur` '
    . 'SET '
    . 'nomUtilisateur = :nomUtil,'
    . 'motDePasse =:mdp,'
    . 'estAdministrateur =:estAdmin '
    . 'WHERE '
    . 'numero = :idUtil');

    $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);

  } else {
    $stmt = $bdd->prepare('UPDATE tbl_utilisateur '
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
  $stmt = $bdd->prepare('DELETE FROM `tbl_utilisateur` WHERE numero = :idUtil');

  $stmt->bindParam(':idUtil', $iIDUtil, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo "L'utilisateur a été supprimé.";
  } else {
    echo "ERREUR : L'utilisateur n'a pas pu être supprimé de la base de données.";
  }

  return $bdd->errorInfo();
}
