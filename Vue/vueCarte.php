<?php
ob_start();
$listeEleves = getElevesParClasse($_SESSION['classeUtilisateur']);
$localitesRandom = getLocaliteRandom()
?>
<script type="text/javascript">
    $('#titrePage').text("Geocoding - Carte")
    
    //Charge la liste des élèves de la classe dans une variable JS
    var lstEleves = <?php echo json_encode($listeEleves); ?>;
    var objLocaliteRandom = <?php echo json_encode($localitesRandom); ?>;
</script>

<!-- Info Connexion + bouton pour se déconnecter -->
<form method="post" action="index.php">
    <p>
        <!-- Affiche le nom d'utilisateur + la classe de celui-ci -->
        <input type='submit' name="envoyer" id="envoyer" value="Se déconnecter" />
        <label for="envoyer"><?= "Utilisateur connecté : " . strtoupper($_SESSION['nomUtilisateur']) . " - " . strtoupper($_SESSION['classeUtilisateur']) ?></label>
        <input type="hidden" name="action" id="action" value="deconnexion">       
    </p>
</form>

<br><br>

<!-- Liste déroulante des élèves de la classe -->
<select name="listeEleveClasse" id="listeEleveClasse" size="1">
    <!-- Option sélectionnée par défaut -->
    <option disabled selected value> -- Sélectionnez un élève -- </option>
    <?php
    foreach ($listeEleves as $eleve) {
        $nomEleve = $eleve['nomEleve'];
        $prenomEleve = $eleve['prenomEleve'];
        $idEleve = $eleve['numero'];

        echo '<option value="' . $idEleve . '">' . $prenomEleve . " " . $nomEleve . '</option> ';
    }
    ?>
</select>


<!-- Bouton pour l'édition des élèves -->
<?php if ($_SESSION['estAdmin'] == 1) { ?>
    <form method="post" action="index.php">
        <input type="hidden" name="action" id="action" value="edition">
        <input type='submit' name="envoyer" id="envoyer" value="Edition des élèves" />
    </form>
<?php } ?>



<!-- Affiche la carte Google Maps -->
<iframe id="frameCarte"
        width="600"
        height="600"
        frameborder="0" style="border:0"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAZSoG_9eDofU0bkA82o_9VT6e1jF-3vrs&q=Store+Kongensgade+49,1264+København+K&maptype=satellite" allowfullscreen>
</iframe>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';

