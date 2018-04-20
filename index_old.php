<?php
session_start();

require_once('Modele/connexionBD.php');

require_once 'Modele/modele.php';
require_once 'Modele/app_modele.php';
require_once 'Modele/user_modele.php';
require_once 'Controleur/controleurUser.php';
require_once 'Controleur/controleurPrincipal.php';
require_once 'Controleur/controleurAdministration.php';

//Initialisation Contrôleurs
$controleurPrincipal = new controleurPrincipal();
$controleurUser = new controleurUser();
$controleurAdministration = new controleurAdministration();

//SAFE MODE
//set_error_handler('gestionnaireErreur');

//POUR CHANGER LE FUSEAU HORAIRE
date_default_timezone_set('Europe/Paris');

$controleurPrincipal->initialiserBD();

$_SESSION['LED'] = (int) exec('cat /sys/class/gpio/gpio68/value');
$_SESSION['modeConnexion'] = getModeConnexion();

//CONTROLEUR ADMINISTRATEUR ==============================================
if (isset($_SESSION['modeAdmin'])) {
  if (isset($_POST["action"])) {
    //var_dump($_POST["action"]);
    switch ($_POST["action"]) {
      case "deconnexion":
      $controleurUser->deconnexion();
      break;
      case "supprimerLog":
      $controleurAdministration->supprimerLog();
      break;
      case "enregistrerParamConnexion":
      $controleurAdministration->enregistrerParamConnexion();
      break;
      case "enregistrerParamLED":
      $controleurAdministration->enregistrerParamLED();
      break;
      case "enregistrerParamSecurite":
      $controleurAdministration->enregistrerParamSecurite();
      break;
      case "ajouterUtil":
      $controleurAdministration->ajouterUtil();
      break;
      case "modifierUtil":
        $controleurAdministration->modifierUtil();
        break;
        case "supprimerUtil":
        $controleurAdministration->supprimerUtil();
        break;
        default:
        $controleurPrincipal->pageAdministration();
        break;
      }
    }

    //Le GET sert pour la redirection simple vers une page
    else if (isset($_GET["action"])) {
      //var_dump($_POST["action"]);
      switch ($_GET["action"]) {
        case "pageAdministration":
        $controleurPrincipal->pageAdministration();
        break;
        case "pageEditionUtil":
        $controleurPrincipal->pageEditionUtil();
        break;
        case "pageLogsConnexion":
        $controleurPrincipal->pageLogsConnexion();
        break;
        case "deconnexion":
        $controleurUser->deconnexion();
        break;
        default:
        $controleurPrincipal->pageAdministration();
        break;
      }
    } else {
      $controleurPrincipal->pageAdministration();
    }
  }

  //CONTROLEUR UTILISATEUR ================================================================
  if (isset($_SESSION["UtilisateurConnecte"]) && !(isset($_SESSION['modeAdmin']))) {
    if (isset($_POST["action"])) {
      //var_dump($_POST["action"]);
      switch ($_POST["action"]) {
        case "deconnexion":
        $controleurUser->deconnexion();
        break;
        case "LedOnOff":
        $controleurPrincipal->ledOnOff();
        break;
        case "Impulsion":
        $controleurPrincipal->ledImpulsion();
        break;
        default:
        $controleurPrincipal->pagePrincipale();
        break;
      }
    } else {
      //Le GET sert pour la redirection simple vers une page
      if (isset($_GET["action"])) {
        //var_dump($_POST["action"]);
        switch ($_GET["action"]) {
          case "pagePrincipale":
          $controleurPrincipal->pagePrincipale();
          break;
          case "deconnexion":
          $controleurUser->deconnexion();
          break;
          default:
          $controleurPrincipal->pagePrincipale();
          break;
        }
      } else {
        $controleurPrincipal->pagePrincipale();
      }
    }
  }

  //SI UTILISATEUR N'EST PAS CONNECTE
  elseif (!(isset($_SESSION['modeAdmin']))) {
    if (isset($_POST["action"])) {

      switch ($_POST["action"]) {
        case "connexion":
        $controleurUser->connexion();
        break;
        case "connexionAdmin":
        $controleurUser->connexionAdmin();
        break;
        default:
        $controleurPrincipal->pageConnexion();
        break;
      }
    } else {
      if (isset($_GET["action"])) {

        switch ($_GET["action"]) {
          case "pageConnexionAdmin":
          $controleurPrincipal->pageConnexionAdmin();
          break;
          case "pageConnexion":
          $controleurPrincipal->pageConnexion();
          break;
          default:
          $controleurPrincipal->pageConnexion();
          break;
        }
      } else {
        $controleurPrincipal->pageConnexion();
      }
    }
  }