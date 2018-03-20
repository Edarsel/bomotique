<?php
session_start();

require_once 'Modele/modele.php';
require_once 'Modele/app_modele.php';
require_once 'Modele/user_modele.php';
require_once 'Controleur/user.php';
require_once 'Controleur/principal.php';

//SAFE MODE
//set_error_handler('gestionnaireErreur');

//POUR CHANGER LE FUSEAU HORAIRE
date_default_timezone_set('Europe/Paris');

initialiserBD();

$_SESSION['LED'] = (int) exec('cat /sys/class/gpio/gpio68/value');
$_SESSION['modeConnexion'] = getModeConnexion();

//CONTROLEUR ADMINISTRATEUR ==============================================
if (isset($_SESSION['modeAdmin'])) {
  if (isset($_POST["action"])) {
    //var_dump($_POST["action"]);
    switch ($_POST["action"]) {
      case "deconnexion":
      deconnexion();
      break;
      case "supprimerLog":
      supprimerLog();
      break;
      case "enregistrerParamConnexion":
      enregistrerParamConnexion();
      break;
      case "enregistrerParamLED":
      enregistrerParamLED();
      break;
      case "enregistrerParamSecurite":
      enregistrerParamSecurite();
      break;
      case "ajouterUtil":
      ajouterUtil();
      break;
      case "modifierUtil":
        modifierUtil();
        break;
        case "supprimerUtil":
        supprimerUtil();
        break;
        default:
        pageAdministration();
        break;
      }
    }

    //Le GET sert pour la redirection simple vers une page
    else if (isset($_GET["action"])) {
      //var_dump($_POST["action"]);
      switch ($_GET["action"]) {
        case "pageAdministration":
        pageAdministration();
        break;
        case "pageEditionUtil":
        pageEditionUtil();
        break;
        case "pageLogsConnexion":
        pageLogsConnexion();
        break;
        case "deconnexion":
        deconnexion();
        break;
        default:
        pageAdministration();
        break;
      }
    } else {
      pageAdministration();
    }
  }

  //CONTROLEUR UTILISATEUR ================================================================
  if (isset($_SESSION["UtilisateurConnecte"]) && !(isset($_SESSION['modeAdmin']))) {
    if (isset($_POST["action"])) {
      //var_dump($_POST["action"]);
      switch ($_POST["action"]) {
        case "deconnexion":
        deconnexion();
        break;
        case "LedOnOff":
        ledOnOff();
        break;
        case "Impulsion":
        ledImpulsion();
        break;
        default:
        pagePrincipale();
        break;
      }
    } else {
      //Le GET sert pour la redirection simple vers une page
      if (isset($_GET["action"])) {
        //var_dump($_POST["action"]);
        switch ($_GET["action"]) {
          case "pagePrincipale":
          pagePrincipale();
          break;
          case "deconnexion":
          deconnexion();
          break;
          default:
          pagePrincipale();
          break;
        }
      } else {
        pagePrincipale();
      }
    }
  }

  //SI UTILISATEUR N'EST PAS CONNECTE
  elseif (!(isset($_SESSION['modeAdmin']))) {
    if (isset($_POST["action"])) {

      switch ($_POST["action"]) {
        case "connexion":
        connexion();
        break;
        case "connexionAdmin":
        connexionAdmin();
        break;
        default:pageConnexion();
        break;
      }
    } else {
      if (isset($_GET["action"])) {

        switch ($_GET["action"]) {
          case "pageConnexionAdmin":
          pageConnexionAdmin();
          break;
          case "pageConnexion":
          pageConnexion();
          break;
          default:pageConnexion();
          break;
        }
      } else {
        pageConnexion();
      }
    }
  }
