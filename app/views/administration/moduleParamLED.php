<!-- FORMULAIRE DES PARAMETRES DE LA LED-->
<p class="h4" data-toggle="collapse" data-target="#formParamLED" aria-expanded="false" aria-controls="formParamLED">Paramètres LED <img src="chevron-down.svg" height="30px" width="30px" /></p>
<form method="post" action="index.php" name="formParamLED" id="formParamLED" autocomplete="on" class="collapse">
  <div class="custom-controls-stacked">


    <div class="form-group">
      <label for="tempsImpulsion">Temps de l'impulsion (ms) :</label><br>
      <input type="text" id="tempsImpulsion" name="tempsImpulsion" placeholder="temps en milliseconde" value="<?= $infoApp->tempsImpulsion ?>" />
    </div>
  </div>

  <input type="hidden" name="action" id="action" value="enregistrerParamLED">
  <input type='button' name="enrImpulsLED" id="enrImpulsLED" value="Enregistrer Paramètres LED" class="btn btn-primary" /><br>
</form>
