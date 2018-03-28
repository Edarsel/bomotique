<?php
  class controleurPages {
    public function vueConnexion() {
      if (isset($_SESSION["UtilisateurConnecte"]))
      {
        //pagePrincipale();
      }else{
        $pseudo = "";
        $mdp = "";

        if (isset($_POST['pseudo'])) {
          if ((is_null($_POST['pseudo'])) == false) {
            $pseudo = controleurUser::protectionXSS($_POST['pseudo']);
            if (isset($_POST['pass'])) {
              if ((is_null(controleurUser::protectionXSS($_POST['pass']))) == false) {
                $mdp = controleurUser::protectionXSS($_POST['pass']);
                require_once 'Vue/vueConnexion.php';
              }
            }
          }
        } else {
          require_once 'Vue/vueConnexion.php';
        }
      }
    }

    public function vueConnexionAdmin() {
      $pseudo = "";
      $mdp = "";

      if (isset($_POST['pseudo'])) {
        if ((is_null($_POST['pseudo'])) == false) {
          $pseudo = controleurUser::protectionXSS($_POST['pseudo']);
          if (isset($_POST['pass'])) {
            if ((is_null(controleurUser::protectionXSS($_POST['pass']))) == false) {
              $mdp = controleurUser::protectionXSS($_POST['pass']);

              require 'Vue/vueConnexionAdmin.php';
              require 'Vue/administration/menuAdministration.php';
            }
          }
        }
      } else {
        require 'Vue/vueConnexionAdmin.php';
      }
    }

    public function vuePrincipale() {
      if (isset($_SESSION["UtilisateurConnecte"]))
      {
        require 'Vue/vuePrincipale.php';
      }
      else{
        //pageConnexion();
      }
    }

    public function vueEditionUtil()
    {
      if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
      {
        require 'Vue/administration/vueEditionUtil.php';
      }else{
        pageConnexion();
      }
    }

    public function vueAdministration(){
      if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
      {
        require 'Vue/administration/vueAdministration.php';
      }else{
        pageConnexion();
      }
    }

    public function vueLogsConnexion(){
      if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
      {
        $listeLogs = getLogsConnexion();
        require 'Vue/administration/vueLogsConnexion.php';
      }else{
        pageConnexion();
      }
    }

    public function error() {
      require_once('views/pages/error.php');
    }
  }
