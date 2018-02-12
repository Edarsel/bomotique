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