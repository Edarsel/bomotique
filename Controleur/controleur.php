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
    }
  }

}

function supprimerUtil() {

  $iIDUtil;


  if (isset($_POST['idUtil'])) {
    if (protectionXSS($_POST['idUtil']) != null) {
      $iIDUtil = protectionXSS($_POST['idUtil']);

      if (!($_SESSION['UtilisateurConnecte']->numero == $iIDUtil))
      {
        delUtilisateur($iIDUtil);
      }else
      {
        echo "Vous ne pouvez pas supprimer votre compte utilisateur !";
      }
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

function captchaValide() {
  $googleResponse = false;

  $response = $_POST['g-recaptcha-response'];
  $secret = '6Le3mjkUAAAAACQWVXbPj5LHOzMIqYKWMt5M0d92';


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

  return $googleResponse;
}

function protectionXSS($var){
  $var = trim($var);

  $var = strip_tags($var);
  $var = htmlspecialchars($var);

  return $var;
}
