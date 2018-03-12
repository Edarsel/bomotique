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
                    erreurConnexion();
                }
            } else {
              ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
                require 'Vue/vueConnexion.php';
                echo '<script>';
                echo 'alert("ERREUR : Le captcha n\'est pas valide !");';
                echo "$('#pass').val('');";
                echo "$('#pass').focus();";
                echo '</script>';
            }
        } else {
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
            require 'Vue/vueConnexion.php';
            erreurConnexion();
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
                            erreurConnexion();
                        }
                    }else{
                      ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
                        require 'Vue/vueConnexion.php';
                        erreurConnexion();
                    }

                } else {
                  ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
                    require 'Vue/vueConnexion.php';
                    echo '<script>';
                    echo 'alert("ERREUR : Le captcha n\'est pas valide !");';
                    echo "$('#pass').val('');";
                    echo "$('#pass').focus();";
                    echo '</script>';
                }
            } else {
              ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
                require 'Vue/vueConnexion.php';
                erreurConnexion();
            }
        }else{
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, false, null);
          require 'Vue/vueConnexion.php';
          echo '<script>';
          echo 'alert("ERREUR : Entrez un nom d\'utilisateur !");';
          echo "$('#pass').val('');";
          echo "$('#pass').focus();";
          echo '</script>';
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
                            erreurConnexion();
                        }
                    }else{
                      ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
                        require 'Vue/vueConnexionAdmin.php';
                        erreurConnexion();
                    }

                } else {
                  ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
                    require 'Vue/vueConnexionAdmin.php';
                    echo '<script>';
                    echo 'alert("ERREUR : Le captcha n\'est pas valide !");';
                    echo "$('#pass').val('');";
                    echo "$('#pass').focus();";
                    echo '</script>';
                }
            } else {
              ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
                require 'Vue/vueConnexionAdmin.php';
                erreurConnexion();
            }
        }else
        {
          ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
            require 'Vue/vueConnexionAdmin.php';
            erreurConnexion();
        }
    }
    else
    {
      ajoutLogConnexion($empreinteClient,date("Y-m-d H:i:s", time()), false, true, null);
        require 'Vue/vueConnexionAdmin.php';
        erreurConnexion();
    }
}

function ajoutLogConnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur)
{
  addLogconnexion($empreinteClient, $dateHeure, $connexionReussie, $estAdministrateur, $utilisateur);
}

function erreurConnexion() {
    echo '<script>';
    echo 'alert("Echec de connexion. Veuillez ressaisir le mot de passe.");';
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
