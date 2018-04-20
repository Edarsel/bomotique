<?php
ob_start();

$infoApp = getInfoApplication();
require('Vue/administration/menuAdministration.php');
?>
<script type="text/javascript">
$('#titrePage').text("Bomotique - Page Administration")
$('#titreContenu').text("Page Administration")
</script>

<!-- FORMULAIRE DES PARAMETRES DE CONNEXION-->
<?php
require('Vue/administration/moduleParamConnexion.php');
?>
<hr class="my-4">
<!-- FORMULAIRE DES PARAMETRES DE SECURITE-->
<?php
require('Vue/administration/moduleParamSecurite.php');
?>
<hr class="my-4">
<!-- FORMULAIRE DES PARAMETRES DE LA LED-->
<?php
require('Vue/administration/moduleParamLED.php');
?>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';
