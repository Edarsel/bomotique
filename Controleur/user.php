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
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), true, false, null);
          require 'Vue/vuePrincipale.php';
        } else {
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
          require 'Vue/vueConnexion.php';
          erreurConnexion("Veuillez ressaisir le mot de passe.");
        }
      } else {
        ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
        require 'Vue/vueConnexion.php';
        erreurConnexion("Le captcha n'est pas valide !");
      }
    } else {
      ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
      require 'Vue/vueConnexion.php';
      erreurConnexion("Veuillez ressaisir le mot de passe.");
    }
  }
  //Si vrai => Mode utilisateur
  else if (isset($_POST['pseudo']) && isset($_POST['pass']) && !($_SESSION['modeConnexion']))
  {
    if ((protectionXSS($_POST['pseudo'])) != "") {
      $pseudo = protectionXSS(strtolower($_POST['pseudo']));

      if ((protectionXSS($_POST['pass'])) != "") {
        if (captchaValide()) {
          $mdp = $_POST['pass'];
          $objUtil = getUtilisateur($pseudo);
          if ($objUtil){
            if (verifierCompteBloque($objUtil->numero) == false){
              if (password_verify($mdp, $objUtil->motDePasse)) {
                $objParam = new stdClass();
                $objParam->estAdministrateur=$objUtil->estAdministrateur;
                $objParam->nomUtilisateur=$objUtil->nomUtilisateur;
                $objParam->numero=$objUtil->numero;
                $_SESSION['UtilisateurConnecte']=$objParam;
                ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), true, false, $objUtil->numero);
                require 'Vue/vuePrincipale.php';
              } else {
                ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, $objUtil->numero);
                require 'Vue/vueConnexion.php';
                erreurConnexion("Veuillez ressaisir le mot de passe.");
              }
            }else {
              require 'Vue/vueConnexion.php';
              erreurConnexion("Ce compte utilisateur est bloqué. Veuillez réessayer plus tard.");
            }
          }else{
            ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
            require 'Vue/vueConnexion.php';
            erreurConnexion("Veuillez ressaisir le mot de passe.");
          }

        } else {
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
          require 'Vue/vueConnexion.php';
          erreurConnexion("Le captcha n'est pas valide !");
        }
      } else {
        ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
        require 'Vue/vueConnexion.php';
        erreurConnexion("Veuillez ressaisir le mot de passe.");
      }
    }else{
      ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
      require 'Vue/vueConnexion.php';
      erreurConnexion("Entrez un nom d'utilisateur !");
    }
  }
}

function connexionAdmin(){
  $pseudo = "";
  $empreinteClient = $_SERVER['REMOTE_ADDR'];



  if (isset($_POST['pseudo']) && isset($_POST['pass']))
  {
    if ((protectionXSS($_POST['pseudo'])) != "") {
      $pseudo = protectionXSS(strtolower($_POST['pseudo']));

      if ((protectionXSS($_POST['pass'])) != "") {
        if (captchaValide()) {
          $mdp = $_POST['pass'];
          $objUtil = getUtilisateur($pseudo);
          if ($objUtil){
            if (verifierCompteBloque($objUtil->numero) == false){
              if (password_verify($mdp, $objUtil->motDePasse) && $objUtil->estAdministrateur) {
                $objParam = new stdClass();
                $objParam->estAdministrateur=$objUtil->estAdministrateur;
                $objParam->nomUtilisateur=$objUtil->nomUtilisateur;
                $objParam->numero=$objUtil->numero;
                $_SESSION['UtilisateurConnecte']=$objParam;
                $_SESSION['modeAdmin']=1;
                //var_dump($_SESSION['UtilisateurConnecte']);
                ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), true, true, $objUtil->numero);
                require 'Vue/administration/vueAdministration.php';
              } else {
                ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, $objUtil->numero);
                require 'Vue/vueConnexionAdmin.php';
                erreurConnexion("Veuillez ressaisir le mot de passe.");
              }
            }else {
              require 'Vue/vueConnexion.php';
              erreurConnexion("Ce compte utilisateur est bloqué. Veuillez réessayer plus tard.");
            }
          }else{
            ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
            require 'Vue/vueConnexionAdmin.php';
            erreurConnexion("Veuillez ressaisir le mot de passe.");
          }

        } else {
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
          require 'Vue/vueConnexionAdmin.php';
          erreurConnexion("Le captcha n'est pas valide !");
        }
      } else {
        ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
        require 'Vue/vueConnexionAdmin.php';
        erreurConnexion("Veuillez ressaisir le mot de passe.");
      }
    }else
    {
      ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
      require 'Vue/vueConnexionAdmin.php';
      erreurConnexion("Veuillez ressaisir le mot de passe.");
    }
  }
  else
  {
    ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
    require 'Vue/vueConnexionAdmin.php';
    erreurConnexion("Veuillez ressaisir le mot de passe.");
  }
}

function verifierCompteBloque($idUtilisateur){
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
    $Temps = strtotime($listeLogsUtil[$i]->dateHeure);
    $TempsPrecedent = strtotime($listeLogsUtil[$i+1]->dateHeure);
    $diffTemps = date("Y-m-d H:i:s", $Temps - $TempsPrecedent);

    while ($finTentative == false) {

      if ($listeLogsUtil[$i]->connexionReussie) {
        $finTentative = true;
      }
      else if ($diffTemps > $tempsIntervale) {
        $finTentative = true;
      }
      else {
        echo $diffTemps."\n";
        $i+=1;
      }



      if (isset($listeLogsUtil[$i]) && isset($listeLogsUtil[$i+1]))
      {
        $Temps = strtotime($listeLogsUtil[$i]->dateHeure);
        $TempsPrecedent = strtotime($listeLogsUtil[$i+1]->dateHeure);
        $diffTemps = date("Y-m-d H:i:s", $Temps - $TempsPrecedent);
      }else{
        $finTentative = true;
      }
    }
  }

  $i+=1;
  echo $i;

  if ($i >= $parametreApplication->nbTentative) {

    $Temps = strtotime("now");
    $TempsPrecedent = strtotime($listeLogsUtil[0]->dateHeure);
    $diffTemps = date("Y-m-d H:i:s", $Temps - $TempsPrecedent);

    if ($diffTemps < $tempsBlocage)
    {
      echo "BLOQUE";
      return true;
    }
    else {
      echo "PAS BLOQUE";
      return false;
    }
  }
  else {
    echo "PAS BLOQUE 1";
    return false;
  }

}

function ajoutLogConnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur)
{
  addLogconnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur);
}

function erreurConnexion($messageAAfficher) {
  echo '<script>';
  echo 'alert("Echec de connexion. '.$messageAAfficher.'");';
  echo "$('#pass').val('');";
  echo "$('#pass').focus();";
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
