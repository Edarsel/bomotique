<?php

function connexion() {
  $pseudo = "";
  $mdp = "";
  $objUtil;
  $empreinteClient = $_SERVER['REMOTE_ADDR'];

  //Si vrai => Mode password
  if (!(isset($_POST['pseudo'])) && isset($_POST['pass']) && $_SESSION['modeConnexion'])
  {
    if ((protectionXSS($_POST['pass'])) != "") {
      if (captchaValide()) {
        $mdp = $_POST['pass'];
        $objApp = getInfoApplication();

        if (password_verify($mdp, $objApp->motDePasse)) {
          //Création d'un objet
          $objParam = new stdClass();
          $objParam->estAdministrateur=0;
          $objParam->nomUtilisateur=null;
          $objParam->numero=null;
          $_SESSION['UtilisateurConnecte']=$objParam;
          //var_dump($_SESSION['UtilisateurConnecte']);
          ajoutLogConnexion($empreinteClient,time(), true, false, null);
          require 'Vue/vuePrincipale.php';
        } else {
          ajoutLogConnexion($empreinteClient,time(), false, false, null);
          require 'Vue/vueConnexion.php';
          erreurConnexion("Veuillez resaisir le mot de passe.");
        }
      } else {
        ajoutLogConnexion($empreinteClient,time(), false, false, null);
        require 'Vue/vueConnexion.php';
        erreurConnexion("Le captcha n'est pas valide !");
      }
    } else {
      ajoutLogConnexion($empreinteClient,time(), false, false, null);
      require 'Vue/vueConnexion.php';
      erreurConnexion("Veuillez resaisir le mot de passe.");
    }
  }
  //Si vrai => Mode utilisateur
  else if (isset($_POST['pseudo']) && isset($_POST['pass']) && !($_SESSION['modeConnexion']))
  {
    if ((protectionXSS($_POST['pseudo'])) != "") {
      $pseudo = protectionXSS(strtolower($_POST['pseudo']));
      $objUtil = getUtilisateurParID($pseudo);

      if ((protectionXSS($_POST['pass'])) != "") {
        if (captchaValide()) {
          $mdp = $_POST['pass'];
          if ($objUtil){
            $tempsRestant;
            if (verifierCompteBloque($objUtil->numero,$tempsRestant) == false){
              if (password_verify($mdp, $objUtil->motDePasse)) {
                $objParam = new stdClass();
                $objParam->estAdministrateur=$objUtil->estAdministrateur;
                $objParam->nomUtilisateur=$objUtil->nomUtilisateur;
                $objParam->numero=$objUtil->numero;
                $_SESSION['UtilisateurConnecte']=$objParam;
                ajoutLogConnexion($empreinteClient,time(), true, false, $objUtil->numero);
                require 'Vue/vuePrincipale.php';
              } else {
                ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
                require 'Vue/vueConnexion.php';
                erreurConnexion("Veuillez resaisir le mot de passe.");
              }
            }else {
              require 'Vue/vueConnexion.php';
              erreurConnexion("Ce compte utilisateur a été bloqué. Veuillez réessayer dans ".$tempsRestant." .");
            }
          }else{
            ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
            require 'Vue/vueConnexion.php';
            erreurConnexion("Veuillez resaisir le mot de passe.");
          }

        } else {
          ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
          require 'Vue/vueConnexion.php';
          erreurConnexion("Le captcha n\'est pas valide !");
        }
      } else {
        ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
        require 'Vue/vueConnexion.php';
        erreurConnexion("Veuillez resaisir le mot de passe.");
      }
    }else{
      ajoutLogConnexion($empreinteClient,time(), false, false, null);
      require 'Vue/vueConnexion.php';
      erreurConnexion("Choisissez un utilisateur !");
    }
  }
  else{
    ajoutLogConnexion($empreinteClient,time(), false, false, null);
    require 'Vue/vueConnexion.php';
    erreurConnexion("Choisissez un utilisateur !");
  }
}

function connexionAdmin(){
  $pseudo = "";
  $empreinteClient = $_SERVER['REMOTE_ADDR'];

  if (isset($_POST['pseudo']) && isset($_POST['pass']))
  {
    if ((protectionXSS($_POST['pseudo'])) != "") {
      $pseudo = protectionXSS(strtolower($_POST['pseudo']));
      $objUtil = getUtilisateurParID($pseudo);

      if ((protectionXSS($_POST['pass'])) != "") {
        if (captchaValide()) {
          $mdp = $_POST['pass'];

          if ($objUtil){
            $tempsRestant;
            if (verifierCompteBloque($objUtil->numero,$tempsRestant) == false){
              if (password_verify($mdp, $objUtil->motDePasse) && $objUtil->estAdministrateur) {
                $objParam = new stdClass();
                $objParam->estAdministrateur=$objUtil->estAdministrateur;
                $objParam->nomUtilisateur=$objUtil->nomUtilisateur;
                $objParam->numero=$objUtil->numero;
                $_SESSION['UtilisateurConnecte']=$objParam;
                $_SESSION['modeAdmin']=1;
                //var_dump($_SESSION['UtilisateurConnecte']);
                ajoutLogConnexion($empreinteClient,time(), true, true, $objUtil->numero);
                require 'Vue/administration/vueAdministration.php';
              } else {
                ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
                require 'Vue/vueConnexionAdmin.php';
                erreurConnexion("Veuillez resaisir le mot de passe.");
              }
            }else {
              require 'Vue/vueConnexionAdmin.php';
              erreurConnexion("Ce compte utilisateur a été bloqué. Veuillez réessayer dans ".$tempsRestant." .");
            }
          }else{
            ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
            require 'Vue/vueConnexionAdmin.php';
            erreurConnexion("Veuillez resaisir le mot de passe.");
          }

        } else {
          ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
          require 'Vue/vueConnexionAdmin.php';
          erreurConnexion("Le captcha n\'est pas valide !");
        }
      } else {
        ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
        require 'Vue/vueConnexionAdmin.php';
        erreurConnexion("Veuillez resaisir le mot de passe.");
      }
    }else
    {
      ajoutLogConnexion($empreinteClient,time(), false, true, null);
      require 'Vue/vueConnexionAdmin.php';
      erreurConnexion("Veuillez resaisir le mot de passe.");
    }
  }
  else
  {
    ajoutLogConnexion($empreinteClient,time(), false, true, null);
    require 'Vue/vueConnexionAdmin.php';
    erreurConnexion("Veuillez resaisir le mot de passe.");
  }
}

function verifierCompteBloque($idUtilisateur, &$tempsRestant){
  $listeLogsUtil = getLogsConnexionParUtilisateur($idUtilisateur);

  if (isset($listeLogsUtil) == false)
  {
    return false;
  }

  $parametreApplication = getInfoApplication();

  $tempsBlocage = date('1970-01-01 H:i:s',strtotime($parametreApplication->tempsBlocage));
  $tempsIntervale = date('1970-01-01 H:i:s',strtotime($parametreApplication->tempsIntervaleTentative));

  $i=0;
  $finTentative = false;

  if (isset($listeLogsUtil[$i]) && isset($listeLogsUtil[$i+1]))
  {
    $Temps = date("Y-m-d H:i:s", strtotime($listeLogsUtil[$i]->dateHeure));
    $TempsPrecedent = date("Y-m-d H:i:s", strtotime($listeLogsUtil[$i+1]->dateHeure));
    $diffTemps = $Temps - $TempsPrecedent;

    while ($finTentative == false) {

      if ($listeLogsUtil[$i]->connexionReussie) {
        $finTentative = true;
      }
      else if ($diffTemps > $tempsIntervale) {
        $finTentative = true;
      }
      else {

        $i+=1;
      }
//echo $tempsIntervale."\n";
//echo $diffTemps."\n";

      if (isset($listeLogsUtil[$i]) && isset($listeLogsUtil[$i+1]))
      {
        $Temps = date("Y-m-d H:i:s", strtotime($listeLogsUtil[$i]->dateHeure));
        $TempsPrecedent = date("Y-m-d H:i:s", strtotime($listeLogsUtil[$i+1]->dateHeure));
        $diffTemps = $Temps - $TempsPrecedent;
      }else{
        $finTentative = true;
      }
    }
  }

  $i+=1;
  //echo $i;
  //echo date("Y-m-d H:i:s");
  $tempsRestant = null;

  if ($i >= $parametreApplication->nbTentative) {

    $Temps = date("Y-m-d H:i:s");
    $TempsPrecedent = date("Y-m-d H:i:s", strtotime($listeLogsUtil[0]->dateHeure));
    $diffTemps = $Temps - $TempsPrecedent;

    if ($diffTemps < $tempsBlocage)
    {
      // echo "BLOQUE";

      $tempsRestant =  date("Y-m-d H:i:s", strtotime($listeLogsUtil[0]->dateHeure)) + $tempsBlocage - date("Y-m-d H:i:s");
      $tempsRestant = date("H\h i\m s\s",$tempsRestant);
      return true;
    }
    else {
      // echo "PAS BLOQUE";
      return false;
    }
  }
  else {
    // echo "PAS BLOQUE 1";
    return false;
  }

}

function ajoutLogConnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur)
{
  addLogconnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur);
}

function erreurConnexion($messageAAfficher) {
  // echo '<script>';
  // echo 'alert("Echec de connexion. '.$messageAAfficher.'");';
  // echo "$('#pass').val('');";
  // echo "$('#pass').focus();";
  // echo '</script>';
  //echo $messageAAfficher;
  echo '<script>';
  echo "$('#contenuAlert').html('".$messageAAfficher."');";
  echo "$('#exampleModal').modal('show')";
  echo '</script>';
}

function hashMotDePasse($strMdp){
  $strMdp = password_hash($strMdp,PASSWORD_BCRYPT);
  return $strMdp;
}

function deconnexion() {
  session_destroy();
  $pseudo = "";
  $mdp = "";
  require 'Vue/vueConnexion.php';
}
