<!-- FORMULAIRE DES PARAMETRES DE LA LED-->
<form method="post" action="index.php" name="formAdminLED" id="formAdminLED" autocomplete="on">
  <div class="custom-controls-stacked">

    <p class="h4">Paramètres LED</p>
    <div class="form-group">
      <label for="tempsImpulsion">Temps de l'impulsion :</label><br>
      <input type="text" id="tempsImpulsion" name="tempsImpulsion" placeholder="<?= (getTempsImpulsion())->tempsImpulsion; ?> millisecondes" />
    </div>
  </div>

  <input type="hidden" name="action" id="action" value="enregistrerAdminLED">
  <input type='button' name="enrImpulsLED" id="enrImpulsLED" value="Enregistrer Paramètres LED" class="btn btn-primary" /><br>
</form>
