<!-- FORMULAIRE DES PARAMETRES DE CONNEXION-->
<p class="h4" data-toggle="collapse" data-target="#formParamConnexion" aria-expanded="false" aria-controls="formParamConnexion">Mode de connexion <img src="chevron-down.svg" height="30px" width="30px" /></p>
<form method="post" action="index.php" name="formParamConnexion" id="formParamConnexion" autocomplete="off" class="collapse show">
  <div class="custom-controls-stacked">

    <label class="custom-control custom-radio">
      <input type="radio" name="modeConnexion" id="modeCoMDP" value="1" checked class="custom-control-input" <?= ( getModeConnexion() ? "checked='checked'" : "" ) ?> >
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
      <input type="radio" name="modeConnexion" id="modeCoUserMDP" value="0"class="custom-control-input" <?= ( !getModeConnexion() ? "checked='checked'" : "" ) ?>>
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Mode Utilisateur/Mot de Passe</span>
    </label>

    <fieldset class="form-group">
      <a href="index.php?action=pageEditionUtil">Gérer utilisateurs</a><br>
    </fieldset>

  </div>
  <input type="hidden" name="action" id="action" value="enregistrerParamConnexion">
  <input type='button' name="enrModeCo" id="enrModeCo" value="Enregistrer Mode de Connexion" class="btn btn-primary" /><br>
</form>
