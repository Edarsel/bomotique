<?php
ob_start();
?>
<script type="text/javascript">
$('#titrePage').text("Bomotique - Page Administration")
$('#titreContenu').text("Page Administration")

var contenuNavbar=$(`
  <ul class="navbar-nav">
  <li class="nav-item">
  <a class="nav-link" href="index.php?action=pageAdministration">Page Administration</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="index.php?action=pageEditionUtil">Gestion Utilisateurs</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="index.php?action=deconnexion">Déconnexion</a>
  </li>
  </ul>`);
  $('#nav-content').html(contenuNavbar);
</script>


<form method="post" action="index.php" name="formAdmin" id="formAdmin" autocomplete="off">


  <div class="custom-controls-stacked">
    <p class="h4">Mode de connexion</p>
    <label class="custom-control custom-radio">
      <input type="radio" name="modeConnexion" id="modeCoMDP" value="1" checked class="custom-control-input">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Mode Mot de Passe</span>
    </label>

    <fieldset class="form-group">
      <input type="password" name="mdp" id="mdp" placeholder="Nouveau Mot de Passe"><br>
    </fieldset>
    <fieldset class="form-group">
      <input type="password" name="vmdp" id="vmdp" placeholder="Vérification MdP"><br>
    </fieldset>

    <label class="custom-control custom-radio">
      <input type="radio" name="modeConnexion" id="modeCoUserMDP" value="0"class="custom-control-input">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Mode Utilisateur/Mot de Passe</span>
    </label>

    <fieldset class="form-group">
      <a href="index.php?action=pageEditionUtil">Gérer utilisateurs</a><br>
    </fieldset>

  </div>
  <input type="hidden" name="action" id="action" value="enregistrerAdmin">
  <input type='button' name="enrModeCo" id="enrModeCo" value="Enregistrer" class="btn btn-primary" /><br>
</form><br>

<!-- FORMULAIRE DES PARAMETRES DE LA LED -->
<form method="post" action="index.php" name="formAdminLED" id="formAdminLED" autocomplete="on">
  <div class="custom-controls-stacked">

    <p class="h4">Paramètres LED</p>
    <div class="form-group">
      <label for="tempsImpulsion">Temps de l'impulsion :</label><br>
      <input type="text" id="tempsImpulsion" name="tempsImpulsion" placeholder="1000 millisecondes" />
    </div>
  </div>

  <input type="hidden" name="action" id="action" value="enregistrerAdminLED">
  <input type='button' name="enrImpulsLED" id="enrImpulsLED" value="Enregistrer" class="btn btn-primary" /><br>
</form>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';
