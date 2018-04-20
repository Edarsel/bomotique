<?php

class Administration extends Controller
{


  function supprimerLog(){
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];

    // echo $dateDebut;
    // echo $dateFin;

    delLogConnexion($dateDebut, $dateFin);
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

  function enregistrerParamConnexion()
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

  function enregistrerParamLED()
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

  function enregistrerParamSecurite()
  {
    if (isset($_POST['nbTentative']) && isset($_POST['tempsInterTenta']) && isset($_POST['tempsBlocage']))
    {
      $nbTentative = $_POST['nbTentative'];
      $tempsInterTenta = $_POST['tempsInterTenta'];
      $tempsBlocage = $_POST['tempsBlocage'];

      if (is_null($nbTentative) == false && is_int((int)$nbTentative) && (int)$nbTentative > 0)
      {
        $tempsBlocage = date('H:i:s', strtotime($tempsBlocage));
        $tempsInterTenta = date('H:i:s', strtotime($tempsInterTenta));

        uptParamSecurite($nbTentative,$tempsInterTenta,$tempsBlocage);
      }
      else{
        echo "ERREUR ! Entrez un nombre entier !";
      }
    }
    else{
      echo "ERREUR mode connexion";
    }
  }

}
