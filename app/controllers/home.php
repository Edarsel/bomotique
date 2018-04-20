<?php

class Home extends Controller
 {
  //echo "<h1>TEST CONTROLEUR !!!</h1>";

  public function index($name='')
     {
        /* echo 'home/index<br />';
         echo $name;*/
         $this->view('template/header');
         $this->view('home/vuePrincipale');
         $this->view('template/footer');

     }

  function ledImpulsion(){
    exec('echo out > /sys/class/gpio/gpio68/direction');
    $_SESSION['LED'] = (int)exec('cat /sys/class/gpio/gpio68/value');

    $resReq = getTempsImpulsion();
    $temps = $resReq->tempsImpulsion;

    exec('echo 1 > /sys/class/gpio/gpio68/value');
    usleep( $temps * 1000 );
    exec('echo 0 > /sys/class/gpio/gpio68/value');
  }

  function ledOnOff(){
    //Configuration en sortie du port
    exec('echo out > /sys/class/gpio/gpio68/direction');

    if ($_SESSION['LED']==1)
    {
      //Pour éteindre le port
      exec('echo 0 > /sys/class/gpio/gpio68/value');
    }
    elseif ($_SESSION['LED']==0){
      //Pour allumer le port
      exec('echo 1 > /sys/class/gpio/gpio68/value');
    }

    $_SESSION['LED'] = (int)exec('cat /sys/class/gpio/gpio68/value');
  }

  function pagePrincipale(){
    if (isset($_SESSION["UtilisateurConnecte"]))
    {
      require 'Vue/vuePrincipale.php';
    }
    else{
      pageConnexion();
    }
  }

  function pageConnexion() {
    if (isset($_SESSION["UtilisateurConnecte"]))
    {
      pagePrincipale();
    }else{
      $pseudo = "";
      $mdp = "";

      if (isset($_POST['pseudo'])) {
        if ((is_null($_POST['pseudo'])) == false) {
          $pseudo = protectionXSS($_POST['pseudo']);
          if (isset($_POST['pass'])) {
            if ((is_null(protectionXSS($_POST['pass']))) == false) {
              $mdp = protectionXSS($_POST['pass']);
              require 'Vue/vueConnexion.php';
            }
          }
        }
      } else {
        require 'Vue/vueConnexion.php';
      }
    }
  }

  function pageConnexionAdmin() {
    $pseudo = "";
    $mdp = "";

    if (isset($_POST['pseudo'])) {
      if ((is_null($_POST['pseudo'])) == false) {
        $pseudo = protectionXSS($_POST['pseudo']);
        if (isset($_POST['pass'])) {
          if ((is_null(protectionXSS($_POST['pass']))) == false) {
            $mdp = protectionXSS($_POST['pass']);
            require 'Vue/vueConnexionAdmin.php';
          }
        }
      }
    } else {
      require 'Vue/vueConnexionAdmin.php';
    }
  }

  function pageEditionUtil()
  {
    if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
    {
      require 'Vue/administration/vueEditionUtil.php';
    }else{
      pageConnexion();
    }
  }

  function pageAdministration(){
    if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
    {
      require 'Vue/administration/vueAdministration.php';
    }else{
      pageConnexion();
    }
  }

  function pageLogsConnexion(){
    if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
    {
      $listeLogs = getLogsConnexion();
      require 'Vue/administration/vueLogsConnexion.php';
    }else{
      pageConnexion();
    }
  }

    function gestionnaireErreur($errno, $errstr, $errfile, $errline){
      echo "<img src='http://cpc.cx/lxu'><br>";
      echo "Oups, vous n'êtiez pas sensé tombé sur ce b.. sur cette fonctionnalité qui encore en développement. Oui oui. <br><br>";
      echo '<a href="index.php?action=pageConnexion">Je comprends. Je vais oublier tout ce qui s\'est passé et faire comme si de rien n\'était.</a>';
      exit(1);

      return true;

    }

  }
