<!-- FORMULAIRE DES PARAMETRES DE CONNEXION-->
<p class="h4" data-toggle="collapse" data-target="#formParamSecurite" aria-expanded="false" aria-controls="formParamSecurite">Paramètres de sécurité <img src="chevron-down.svg" height="30px" width="30px" /></p>
<form method="post" action="index.php" name="formParamSecurite" id="formParamSecurite" autocomplete="off" class="collapse">

  <div class="custom-controls-stacked" >


    <fieldset class="form-group">
      <label for="nbTentative">Nombre de tentatives de connexion maximum :</label><br>
      <input type="number" id="nbTentative" name="nbTentative" placeholder="1" step="1" min="1" max="50" value="<?= $infoApp->nbTentative ?>"/>
    </fieldset>
    <fieldset class="form-group">
      <label for="tempsInterTenta">Intervale de temps entre chaque tentative :</label><br>
      <input type="time" id="tempsInterTenta" name="tempsInterTenta" placeholder="00:00:00" value="<?= $infoApp->tempsIntervaleTentative ?>" />
    </fieldset>
    <fieldset class="form-group">
      <label for="tempsBlocage">Durée du blocage de compte :</label><br>
      <input type="time" id="tempsBlocage" name="tempsBlocage" placeholder="00:00:00" value="<?= $infoApp->tempsBlocage ?>" />
    </fieldset>

  </div>
  <input type="hidden" name="action" id="action" value="enregistrerParamSecurite">
  <input type='button' name="enrParamSecu" id="enrParamSecu" value="Enregistrer paramètres Sécurité" class="btn btn-primary" /><br>
</form>
