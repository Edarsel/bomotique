<!-- FORMULAIRE DES PARAMETRES DE CONNEXION-->
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
  <input type='button' name="enrModeCo" id="enrModeCo" value="Enregistrer Mode de Connexion" class="btn btn-primary" /><br>
</form>
