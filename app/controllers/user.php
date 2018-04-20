<?php

class User extends Controller
{

  public function index($name='')
     {
        /* echo 'home/index<br />';
         echo $name;*/
         $this->view('template/header');
         //$this->view('login/vueConnexion');
         //$this->view('template/modalAlert');
         $this->view('template/footer');

     }

  public function connexion() {
    $pseudo = "";
    $mdp = "";
    $objUtil;
    $empreinteClient = $_SERVER['REMOTE_ADDR'];

    var_dump($_POST['pseudo']);
    var_dump($_POST['pass']);

    //Si vrai => Mode password
    if (!(isset($_POST['pseudo'])) && isset($_POST['pass']) && $_SESSION['modeConnexion'])
    {
      if ((self::protectionXSS($_POST['pass'])) != "") {
        if (self::captchaValide()) {
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
            self::ajoutLogConnexion($empreinteClient,time(), true, false, null);
            require 'Vue/vuePrincipale.php';
          } else {
            self::ajoutLogConnexion($empreinteClient,time(), false, false, null);
            require 'Vue/vueConnexion.php';
            self::erreurConnexion("Veuillez resaisir le mot de passe.");
          }
        } else {
          self::ajoutLogConnexion($empreinteClient,time(), false, false, null);
          require 'Vue/vueConnexion.php';
          self::erreurConnexion("Le captcha n'est pas valide !");
        }
      } else {
        self::ajoutLogConnexion($empreinteClient,time(), false, false, null);
        require 'Vue/vueConnexion.php';
        self::erreurConnexion("Veuillez resaisir le mot de passe.");
      }
    }
    //Si vrai => Mode utilisateur
    else if (isset($_POST['pseudo']) && isset($_POST['pass']) && !($_SESSION['modeConnexion']))
    {
      if ((self::protectionXSS($_POST['pseudo'])) != "") {
        $pseudo = self::protectionXSS(strtolower($_POST['pseudo']));
        $objUtil = getUtilisateurParID($pseudo);

        if ((self::protectionXSS($_POST['pass'])) != "") {
          if (self::captchaValide()) {
            $mdp = $_POST['pass'];
            if ($objUtil){
              $tempsRestant;
              if (self::verifierCompteBloque($objUtil->numero,$tempsRestant) == false){
                if (password_verify($mdp, $objUtil->motDePasse)) {
                  $objParam = new stdClass();
                  $objParam->estAdministrateur=$objUtil->estAdministrateur;
                  $objParam->nomUtilisateur=$objUtil->nomUtilisateur;
                  $objParam->numero=$objUtil->numero;
                  $_SESSION['UtilisateurConnecte']=$objParam;
                  self::ajoutLogConnexion($empreinteClient,time(), true, false, $objUtil->numero);
                  //require 'Vue/vuePrincipale.php';
                  //controleurPages::vuePrincipale();
                  header("Location: ?controleur=Pages&action=vuePrincipale");
                } else {
                  self::ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
                  require 'Vue/vueConnexion.php';
                  erreurConnexion("Veuillez resaisir le mot de passe.");
                }
              }else {
                require 'Vue/vueConnexion.php';
                self::erreurConnexion("Ce compte utilisateur a été bloqué. Veuillez réessayer dans ".$tempsRestant." .");
              }
            }else{
              self::ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
              require 'Vue/vueConnexion.php';
              self::erreurConnexion("Veuillez resaisir le mot de passe.");
            }

          } else {
            self::ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
            require 'Vue/vueConnexion.php';
            self::erreurConnexion("Le captcha n\'est pas valide !");
          }
        } else {
          self::ajoutLogConnexion($empreinteClient,time(), false, false, $objUtil->numero);
          require 'Vue/vueConnexion.php';
          self::erreurConnexion("Veuillez resaisir le mot de passe.");
        }
      }else{
        self::ajoutLogConnexion($empreinteClient,time(), false, false, null);
        require 'Vue/vueConnexion.php';
        self::erreurConnexion("Choisissez un utilisateur !");
      }
    }
    else{
      self::ajoutLogConnexion($empreinteClient,time(), false, false, null);
      require 'Vue/vueConnexion.php';
      self::erreurConnexion("Choisissez un utilisateur !");
    }
  }

  function connexionAdmin(){
    $pseudo = "";
    $empreinteClient = $_SERVER['REMOTE_ADDR'];

    if (isset($_POST['pseudo']) && isset($_POST['pass']))
    {
      if ((self::protectionXSS($_POST['pseudo'])) != "") {
        $pseudo = self::protectionXSS(strtolower($_POST['pseudo']));
        $objUtil = getUtilisateurParID($pseudo);

        if ((self::protectionXSS($_POST['pass'])) != "") {
          if (self::captchaValide()) {
            $mdp = $_POST['pass'];

            if ($objUtil){
              $tempsRestant;
              if (self::verifierCompteBloque($objUtil->numero,$tempsRestant) == false){
                if (password_verify($mdp, $objUtil->motDePasse) && $objUtil->estAdministrateur) {
                  $objParam = new stdClass();
                  $objParam->estAdministrateur=$objUtil->estAdministrateur;
                  $objParam->nomUtilisateur=$objUtil->nomUtilisateur;
                  $objParam->numero=$objUtil->numero;
                  $_SESSION['UtilisateurConnecte']=$objParam;
                  $_SESSION['modeAdmin']=1;
                  //var_dump($_SESSION['UtilisateurConnecte']);
                  self::ajoutLogConnexion($empreinteClient,time(), true, true, $objUtil->numero);
                  require 'Vue/administration/vueAdministration.php';
                } else {
                  self::ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
                  require 'Vue/vueConnexionAdmin.php';
                  self::erreurConnexion("Veuillez resaisir le mot de passe.");
                }
              }else {
                require 'Vue/vueConnexionAdmin.php';
                self::erreurConnexion("Ce compte utilisateur a été bloqué. Veuillez réessayer dans ".$tempsRestant." .");
              }
            }else{
              self::ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
              require 'Vue/vueConnexionAdmin.php';
              self::erreurConnexion("Veuillez resaisir le mot de passe.");
            }

          } else {
            self::ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
            require 'Vue/vueConnexionAdmin.php';
            self::erreurConnexion("Le captcha n\'est pas valide !");
          }
        } else {
          self::ajoutLogConnexion($empreinteClient,time(), false, true, $objUtil->numero);
          require 'Vue/vueConnexionAdmin.php';
          self::erreurConnexion("Veuillez resaisir le mot de passe.");
        }
      }else
      {
        self::ajoutLogConnexion($empreinteClient,time(), false, true, null);
        require 'Vue/vueConnexionAdmin.php';
        self::erreurConnexion("Veuillez resaisir le mot de passe.");
      }
    }
    else
    {
      self::ajoutLogConnexion($empreinteClient,time(), false, true, null);
      require 'Vue/vueConnexionAdmin.php';
      self::erreurConnexion("Veuillez resaisir le mot de passe.");
    }
  }

  function verifierCompteBloque($idUtilisateur, &$tempsRestant){
    $userModel = $this->model('AppModel');

    $listeLogsUtil = $userModel->getLogsConnexionParUtilisateur($idUtilisateur);

    if (isset($listeLogsUtil) == false)
    {
      return false;
    }

    $parametreApplication = $userModel->getInfoApplication();

    $tempsBlocage = new DateTime($parametreApplication->tempsBlocage);
    $tempsIntervale = new DateTime($parametreApplication->tempsIntervaleTentative);
    $i=0;
    $finTentative = false;

    if (isset($listeLogsUtil[$i]) && isset($listeLogsUtil[$i+1]))
    {
      $Temps = new DateTime($listeLogsUtil[$i]->dateHeure);
      $TempsPrecedent = new DateTime($listeLogsUtil[$i+1]->dateHeure);
      $diffTemps = date_diff($TempsPrecedent,$Temps);

      while ($finTentative == false) {

        if ($listeLogsUtil[$i]->connexionReussie) {
          $finTentative = true;
        }
        else if ($diffTemps->format('%Y%Y-%M-%D %H:%I:%S') > $tempsIntervale->format('0000-00-00 H:i:s')) {
          $finTentative = true;
        }
        else {

          $i+=1;
        }

        // print_r($Temps->format('Y-m-d H:i:s')."<br>");
        // print_r($TempsPrecedent->format('Y-m-d H:i:s')."<br>");
        // print_r($tempsIntervale->format('0000-00-00 H:i:s')."<br>");
        // print_r($diffTemps->format('%Y%Y-%M-%D %H:%I:%S')."<br>");

        if (isset($listeLogsUtil[$i]) && isset($listeLogsUtil[$i+1]))
        {
          $Temps = new DateTime($listeLogsUtil[$i]->dateHeure);
          $TempsPrecedent = new DateTime($listeLogsUtil[$i+1]->dateHeure);
          $diffTemps = date_diff($TempsPrecedent,$Temps);
        }else{
          $finTentative = true;
        }
      }
    }

    //$i+=1;
    //echo $i;
    $tempsRestant = null;

    //Si l'utilisateur a dépassé la limite de tentatives
    if ($i >= $parametreApplication->nbTentative) {
      $Temps = new DateTime(date("Y-m-d H:i:s"));
      $TempsPrecedent = new DateTime($listeLogsUtil[0]->dateHeure);
      $diffTemps = date_diff($TempsPrecedent,$Temps);

      //Si le dernier log ne dépasse pas le durée du blocage
      if ($diffTemps->format('%Y%Y-%M-%D %H:%I:%S') < $tempsBlocage->format('0000-00-00 H:i:s'))
      {
        //echo "BLOQUE";

        //Calcul du temps restant à afficher
        $tempsRestant = new DateTime($listeLogsUtil[0]->dateHeure);
        $tempsRestant = $tempsRestant->add(new DateInterval('PT'.$tempsBlocage->format('H').'H'.$tempsBlocage->format('i').'M'.$tempsBlocage->format('s').'S'));

        $dateActuelle = new DateTime(date("Y-m-d H:i:s"));
        $tempsRestant =  date_diff($tempsRestant,$dateActuelle);
        $tempsRestant = $tempsRestant->format('%HH%IM:%SS');

        //Le compte est bloqué
        return true;
      }
      else {
        //echo "PAS BLOQUE";
        return false;
      }
    }
    else {
      //echo "PAS BLOQUE 1";
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
      //Converti le tableau en chaîne de requête en encodage URL (secret=1234&response=1234)
      curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
      curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($verify);

      curl_close($verify);

      //Converti la chaine JSON vers un tableau associatif
      $res = json_decode($response, TRUE);
      if($res['success'] == 'true') {
        $googleResponse = true;
      }

      return $googleResponse;
    }

    public static function protectionXSS($var){
      $var = trim($var);

      $var = strip_tags($var);
      $var = htmlspecialchars($var);

      return $var;
    }

}
