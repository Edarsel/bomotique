<?php

//Les fonctions commencent par le mot anglais par soucis de simplicité
// Effectue la connexion à la BDD
// Instancie et renvoie l'objet PDO associé
function getBdd() {
  $bdd;

  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', '');
  } else {
    $bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', 'Pass1234');
  }

  //$bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'phpmyadmin', 'pass1234');
  return $bdd;
}

function initDB($strMdp){
  $bdd = getBdd();

  $sql = file_get_contents("Script/initDB.sql");
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
  $stmt->bindParam(':mdp2', $strMdp, PDO::PARAM_STR);
  return $stmt->execute();


  $bdd2 = getBdd();
  $stmt2 = $bdd2->prepare('INSERT INTO tbl_utilisateur (numero,nomUtilisateur, motDePasse, estAdministrateur) VALUES (1,"admin", :mdp2 ,1);
    INSERT INTO tbl_application (numero, estEnModePassword, motDePasse,tempsImpulsion,nbTentative,tempsBlocage,tempsIntervaleTentative) VALUES (1, 1, :mdp, 5000, 5, `00:30:00`, `00:00:02`);');
  $stmt2->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
  $stmt2->bindParam(':mdp2', $strMdp, PDO::PARAM_STR);
  //return $stmt2->execute();
}
