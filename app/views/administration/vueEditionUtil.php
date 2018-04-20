<?php
ob_start();

$listeUtilisateur;
require('Vue/administration/menuAdministration.php');
?>
<script type="text/javascript">
$('#titrePage').text("Bomotique - Gestion Utilisateurs");
$('#titreContenu').text("Gestion Utilisateurs");

var lstUtilisateur;
</script>


<div id="FormulaireEdition">

  <!-- Liste des utilisateurs -->
  <div id="ListBoxUtil">
    <?php
    $listeUtilisateur = getUtilisateurs();
    ?>
    <script type="text/javascript">
    //Charge la liste des élèves de la classe dans une variable JS
    lstUtilisateur = <?php echo json_encode($listeUtilisateur); ?>;
    </script>
    <select name="listeUtilEdition" id="listeUtilEdition" class="custom-select">
      <option disabled value selected> -- Sélectionnez un utilisateur -- </option>
      <?php
      foreach ($listeUtilisateur as $objUtil) {
        $nomUtil = $objUtil->nomUtilisateur;
        $idUtil = $objUtil->numero;
        //var_dump($objUtil);
        echo '<option value="' . $idUtil . '">' . $nomUtil . '</option> ';
      }
      ?>
    </select>

  </div>
  <br>

  <!-- Formulaire pour l'edition des informations de l'élève sélectionné -->
  <form method="post" action="index.php" name="formEdition" id="formEdition" autocomplete="off">

    <h4 class="card-title">Informations de l'utilisateur</h4>

    <fieldset class="form-group"><input type="text" name="nomUtil" id="nomUtil" placeholder="Nom d'utilisateur"></fieldset>

    <fieldset class="form-group"><input type="password" name="mdp" id="mdp" placeholder="Nouveau Mot de Passe"></fieldset>
    <fieldset class="form-group"><input type="password" name="vmdp" id="vmdp" placeholder="Vérification MdP"></fieldset>

    <fieldset class="form-group">
    <label for="adminUtil">Est administrateur : </label>
    <input type="checkbox" name="adminUtil" id="adminUtil" value="on"><br>
    </fieldset>

    <input type="hidden" name="action" id="action" value="">
    <input type="hidden" name="idUtil" id="idUtil" value="">

    <input type="button" name="ajoutUtil" id="ajoutUtil" value="Enregistrer nouvel utilisateur" onclick="" class="btn btn-primary btn-xs-block">
    <input type="button" name="modifUtil" id="modifUtil" value="Modifier utilisateur" onclick="" class="btn btn-secondary btn-xs-block">
    <input type="button" name="supprUtil" id="supprUtil" value="Supprimer utilisateur" onclick="" class="btn btn-danger btn-xs-block">

  </form>

  <form method="post" action="index.php" name="actualiserEdition" id="actualiserEdition" autocomplete="off">
    <input type="hidden" name="action" id="action" value="edition">
  </form>
</div>

<a href="index.php?action=pageAdministration">Page Administration</a>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';
