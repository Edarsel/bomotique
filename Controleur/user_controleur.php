<?php

//echo "<h1>TEST CONTROLEUR !!!</h1>";

function connexion() {
    $pseudo = "";
    $mdp = "";
    $objUtil;

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
                    require 'Vue/vuePrincipale.php';
                } else {
                    require 'Vue/vueConnexion.php';
                    erreurConnexion();
                }
            } else {
                require 'Vue/vueConnexion.php';
                echo '<script>';
                echo 'alert("ERREUR : Le captcha n\'est pas valide !");';
                echo "$('#pass').val('');";
                echo "$('#pass').focus();";
                echo '</script>';
            }
        } else {
            require 'Vue/vueConnexion.php';
            erreurConnexion();
        }
    }
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
                            require 'Vue/vuePrincipale.php';
                        } else {
                            require 'Vue/vueConnexion.php';
                            erreurConnexion();
                        }
                    }else{
                        require 'Vue/vueConnexion.php';
                        erreurConnexion();
                    }
                    
                } else {
                    require 'Vue/vueConnexion.php';
                    echo '<script>';
                    echo 'alert("ERREUR : Le captcha n\'est pas valide !");';
                    echo "$('#pass').val('');";
                    echo "$('#pass').focus();";
                    echo '</script>';
                }
            } else {
                require 'Vue/vueConnexion.php';
                erreurConnexion();
            }
        }
    }
}

function connexionAdmin(){
    $pseudo = "";
    
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
                            require 'Vue/vueAdministration.php';
                        } else {
                            require 'Vue/vueConnexionAdmin.php';
                            erreurConnexion();
                        }
                    }else{
                        require 'Vue/vueConnexionAdmin.php';
                        erreurConnexion();
                    }
                    
                } else {
                    require 'Vue/vueConnexionAdmin.php';
                    echo '<script>';
                    echo 'alert("ERREUR : Le captcha n\'est pas valide !");';
                    echo "$('#pass').val('');";
                    echo "$('#pass').focus();";
                    echo '</script>';
                }
            } else {
                require 'Vue/vueConnexionAdmin.php';
                erreurConnexion();
            }
        }else
        {
            require 'Vue/vueConnexionAdmin.php';
            erreurConnexion();
        }
    }
    else
    {
        require 'Vue/vueConnexionAdmin.php';
        erreurConnexion();
    }
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