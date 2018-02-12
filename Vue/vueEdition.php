<?php
ob_start();

$listeElevesEdition = getEleves();
$listeLocalites = getLocalites();
$listeClasses = getClasses();
?>

<script type="text/javascript">
    $('#titrePage').text("Geocoding - Edition")

    //Charge la liste des élèves de la classe dans une variable JS
    var lstElevesEdition = <?php echo json_encode($listeElevesEdition); ?>;
    var lstLocalites = <?php echo json_encode($listeLocalites); ?>;
    var lstClasses = <?php echo json_encode($listeClasses); ?>;
</script>

<div id="FormulaireEdition">

    <!-- Bouton revenir à la page précédente -->
    <form method="post" action="index.php">
        <input type="hidden" name="action" id="action" value="">
        <input type='submit' name="envoyer" id="envoyer" value="<- Page précédente" />
    </form>

    <br>
    
    <!-- Liste des élèves -->
    <div>
        <label for="listeEleveEdition">Liste des élèves : </label><br>
        <select name="listeEleveEdition" id="listeEleveEdition" size="30">
            <option disabled value> -- Sélectionnez un élève -- </option>
            <?php
            foreach ($listeElevesEdition as $objEleve) {
                $nomEleve = $objEleve['nomEleve'];
                $prenomEleve = $objEleve['prenomEleve'];
                $classeEleve = $objEleve['libelleClasse'];
                $idEleve = $objEleve['numero_eleve'];

                echo '<option value="' . $idEleve . '">' . $prenomEleve . " " . $nomEleve . " - " . $classeEleve . '</option> ';
            }
            ?>
        </select>
        
    </div>
    <br><br>

    <!-- Formulaire pour l'edition des informations de l'élève sélectionné -->
    <form method="post" action="index.php" name="formEleve" id="formEleve" autocomplete="off">
        <p>
            
        <h2>Informations de l'élève</h2>
            
            <label for="nomEleve">Nom * : </label><br>
            <input type="text" name="nomEleve" id="nomEleve">
            <input type="button" name="resetPage" id="resetPage" onclick="actualiserPageEdition()" value="Reset des champs">
            <br><br>
            <label for="prenomEleve">Prénom * : </label><br>
            <input type="text" name="prenomEleve" id="prenomEleve"><br><br>

            <label for="classeEleve">Classe * : </label><br>
            <select name="classeEleve" id="classeEleve">
                <option disabled selected value> -- Sélectionnez une classe -- </option>
                <?php
                foreach ($listeClasses as $objClasse) {
                    $nomClasse = $objClasse['libelleClasse'];
                    $idClasse = $objClasse['numero'];

                    echo '<option value="' . $idClasse . '">' . $nomClasse . '</option> ';
                }
                ?>
            </select><br><br>

            <label for="rueEleve">Rue/Numéro * : </label><br>
            <input type="text" name="rueEleve" id="rueEleve">
            <input type="text" name="rueNumEleve" id="rueNumEleve"><br><br>

            <label for="localiteEleve">Localite * : </label><br>

            <select name="localiteEleve" id="localiteEleve">
                <option disabled selected value> -- Sélectionnez une localité -- </option>
                <?php
                foreach ($listeLocalites as $objLocalite) {
                    $npa = $objLocalite['npaLocalite'];
                    $nomlocalite = $objLocalite['nomLocalite'];
                    $idLocalite = $objLocalite['numero'];

                    echo '<option value="' . $idLocalite . '">' . $nomlocalite . " - " . $npa . '</option> ';
                }
                ?>
            </select><br><br>

            
            <h2>Informations de compte de l'élève (facultatif)</h2>
            
            <label for="userEleve">Nom d'utilisateur : </label><br>
            <input type="text" name="userEleve" id="userEleve"><br><br>
            <label for="adminEleve">Administrateur : </label>
            <input type="checkbox" name="adminEleve" id="adminEleve" value="on"><br><br>
            <label for="mdpEleve">Nouveau mot de passe : </label><br>
            <input type="password" name="mdpEleve" id="mdpEleve"><br><br>
            <label for="vmdpEleve">Vérification mot de passe : </label><br>
            <input type="password" name="vmdpEleve" id="vmdpEleve"><br><br>
            
            <!-- Captcha Google -->
            <div class="g-recaptcha" data-sitekey="6Le3mjkUAAAAAO8iqeRGS4LUlAGgSlhBJ574ZdPi"></div>

            <input type="hidden" name="action" id="action" value="">
            <input type="hidden" name="idEleve" id="idEleve" value="">

            <input type="button" name="ajoutEleve" id="ajoutEleve" value="Enregistrer nouvel élève" onclick="">
            <input type="button" name="modifEleve" id="modifEleve" value="Modifier élève sélectionné" onclick="">
            <input type="button" name="supprEleve" id="supprEleve" value="Supprimer élève sélectionné" onclick="">

            <br><br>
            <label>- Champs obligatoires (*)</label><br>
            <label>- Si un nom d'utilisateur est donnée à un élève, un mot de passe est exigé.</label><br>
            <label>- La suppression d'un nom d'utilisateur entraîne une suppression du mot de passe</label>
        </p>
    </form>

    <form method="post" action="index.php" name="actualiserEdition" id="actualiserEdition" autocomplete="off">
        <input type="hidden" name="action" id="action" value="edition">
    </form>
</div>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';

