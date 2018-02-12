<?php

//echo "<h1>TEST CONTROLEUR !!!</h1>";

function initialiserBD() {
  $strMdp="Pass1234";
  $strMdp=hashMotDePasse($strMdp);
  //var_dump(initDB($strMdp));
  initDB($strMdp);
}

function LedImpulsion(){
  exec('echo out > /sys/class/gpio/gpio68/direction');
  $_SESSION['LED'] = (int)exec('cat /sys/class/gpio/gpio68/value');

  $resReq = getTempsImpulsion();
  $temps = $resReq->tempsImpulsion;

  exec('echo 1 > /sys/class/gpio/gpio68/value');
  usleep( $temps * 1000 );
  exec('echo 0 > /sys/class/gpio/gpio68/value');
}

function LedOnOff(){

  exec('echo out > /sys/class/gpio/gpio68/direction');

  if ($_SESSION['LED']==1)
  {
    exec('echo 0 > /sys/class/gpio/gpio68/value');
    //echo "ETEINT";
  }
  elseif ($_SESSION['LED']==0){
    exec('echo 1 > /sys/class/gpio/gpio68/value');
    //echo "ALLUME";
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
    require 'Vue/vueEditionUtil.php';
  }else{
    pageConnexion();
  }
}

function pageAdministration(){
  if ($_SESSION['UtilisateurConnecte']->estAdministrateur)
  {
    require 'Vue/vueAdministration.php';
  }else{
    pageConnexion();
  }
}

function ajouterUtil()
{

  $strNomUtil = protectionXSS($_POST['nomUtil']);
  $iAdmin = 0;
  $strMdp = protectionXSS($_POST['mdp']);
  $strMdp = hashMotDePasse($strMdp);

  if (isset($_POST['adminUtil'])) {
    if ($_POST['adminUtil'] == "on") {
      $iAdmin = 1;
    }
  }

  //echo "VALEUR SI EST ADMIN : " . $iAdmin;

  addUtilisateur($strNomUtil, $iAdmin, $strMdp);


}

function modifierUtil() {

  $iIDUtil;

  if (isset($_POST['idUtil'])) {
    if (protectionXSS($_POST['idUtil']) != null) {

      $iIDUtil = $_POST['idUtil'];

      $strNomUtil = protectionXSS($_POST['nomUtil']);
      $iAdmin = 0;
      $strMdp = protectionXSS($_POST['mdp']);

      if ($strMdp)
      {
        $strMdp = hashMotDePasse($strMdp);

      }


      if (isset($_POST['adminUtil'])) {
        if ($_POST['adminUtil'] == "on") {
          $iAdmin = 1;
        }
      }

      updtUtilisateur($iIDUtil, $strNomUtil, $iAdmin, $strMdp);
      //echo "L'élève \"" . $strPrenom . " " . $strNom . "\" a été modifié";
    }
  }

}

function supprimerUtil() {

  $iIDUtil;


  if (isset($_POST['idUtil'])) {
    if (protectionXSS($_POST['idUtil']) != null) {

      $iIDUtil = protectionXSS($_POST['idUtil']);

      delUtilisateur($iIDUtil);
      //echo "ID DE L'ELEVE A SUPPRIMER : " . $iIDEleve;
      //echo "L'élève a été supprimé";
    }
  }

}

function enregistrerAdmin()
{
  if (isset($_POST['modeConnexion']))
  {
    $iModeCo = $_POST['modeConnexion'];

    if ($iModeCo == 1)
    {
      $strMdp = protectionXSS($_POST['mdp']);
      $strMdp = hashMotDePasse($strMdp);
      uptModeConnexion($iModeCo, $strMdp);
    }else{
      uptModeConnexion($iModeCo, "");
    }

  }
  else{
    echo "ERREUR mode connexion";
  }
}

function enregistrerAdminLED()
{
  if (isset($_POST['tempsImpulsion']))
  {
    $iTempsImpuls = $_POST['tempsImpulsion'];
    uptTempsImpulsion($iTempsImpuls);
  }
  else{
    echo "ERREUR la valeur de l'impulsion n'a pas été modifié";
  }
}

function deconnexion() {
  session_destroy();
  $pseudo = "";
  $mdp = "";
  require 'Vue/vueConnexion.php';
}

//==================================



function pageCarte() {
  require 'Vue\vueCarte.php';
}

function pageEdition() {
  require 'Vue\vueEdition.php';
}

function ajouterEleve() {
  if (captchaValide()) {
    $strNom = protectionXSS($_POST['nomEleve']);
    $strPrenom = protectionXSS($_POST['prenomEleve']);
    $iClasse = $_POST['classeEleve'];
    $strRue = protectionXSS($_POST['rueEleve']);
    $strNumRue = protectionXSS($_POST['rueNumEleve']); //
    $iLocal = $_POST['localiteEleve'];
    $strUser = protectionXSS($_POST['userEleve']);
    $iAdmin = 0;
    $strMdp = protectionXSS($_POST['mdpEleve']);

    if (isset($_POST['adminEleve'])) {
      if ($_POST['adminEleve'] == "on") {
        $iAdmin = 1;
      }
    }

    //echo "VALEUR SI EST ADMIN : " . $iAdmin;

    addEleve($strNom, $strPrenom, $iClasse, $strRue, $strNumRue, $iLocal, $strUser, $iAdmin, $strMdp);

  }
}

function modifierEleve() {
  if (captchaValide()) {
    $iIDEleve;

    if (isset($_POST['idEleve'])) {
      if (protectionXSS($_POST['idEleve']) != null) {

        $iIDEleve = $_POST['idEleve'];

        $strNom = protectionXSS($_POST['nomEleve']);
        $strPrenom = protectionXSS($_POST['prenomEleve']);
        $iClasse = $_POST['classeEleve'];
        $strRue = protectionXSS($_POST['rueEleve']);
        $strNumRue = protectionXSS($_POST['rueNumEleve']); //
        $iLocal = $_POST['localiteEleve'];
        $strUser = protectionXSS($_POST['userEleve']);
        $iAdmin = 0;
        $strMdp = protectionXSS($_POST['mdpEleve']);

        if (isset($_POST['adminEleve'])) {
          if ($_POST['adminEleve'] == "on") {
            $iAdmin = 1;
          }
        }

        //Si pas de nom d'utilisateur = pas de compte = pas de mot de passe
        if ($strUser == "") {
          $strUser = null;
          $strMdp = null;
        }

        updtEleve($iIDEleve, $strNom, $strPrenom, $iClasse, $strRue, $strNumRue, $iLocal, $strUser, $iAdmin, $strMdp);
        //echo "L'élève \"" . $strPrenom . " " . $strNom . "\" a été modifié";
      }
    }
  }
}

function supprimerEleve() {
  if (captchaValide()) {
    $iIDEleve;


    if (isset($_POST['idEleve'])) {
      if (protectionXSS($_POST['idEleve']) != null) {

        $iIDEleve = protectionXSS($_POST['idEleve']);

        delEleve($iIDEleve);
        //echo "ID DE L'ELEVE A SUPPRIMER : " . $iIDEleve;
        //echo "L'élève a été supprimé";
      }
    }
  }
}


function captchaValide() {
  $googleResponse = false;

  $response = $_POST['g-recaptcha-response'];
  $google_url = "https://www.google.com/recaptcha/api/siteverify";
  $secret = '6Le3mjkUAAAAACQWVXbPj5LHOzMIqYKWMt5M0d92';
  $remoteip = $_SERVER['REMOTE_ADDR'];

  $url = $google_url."?secret=".$secret."&response=".$response;

  $data = array(
    'secret' => $secret,
    'response' => $response
  );

  $verify = curl_init();
  curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);

    curl_close($verify);

    $res = json_decode($response, TRUE);
    if($res['success'] == 'true') {
      $googleResponse = true;}






      /*
      // Ma clé privée
      $secret = "6Le3mjkUAAAAACQWVXbPj5LHOzMIqYKWMt5M0d92";

      // Paramètre renvoyé par le recaptcha
      $response = $_POST['g-recaptcha-response'];

      // On récupère l'IP de l'utilisateur
      $remoteip = $_SERVER['REMOTE_ADDR'];

      $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
      . $secret
      . "&response=" . $response
      . "&remoteip=" . $remoteip;

      $decode = json_decode(file_get_contents($api_url), true);

      if ($decode['success'] == true) {
      // C'est un humain
      $googleResponse = true;
    } else {
    //echo "ERREUR : Le captcha n'est pas valide !";
  }*/

  return $googleResponse;
}

function protectionXSS($var){
  $var = trim($var);

  $var = strip_tags($var);
  $var = htmlspecialchars($var);

  return $var;
}
