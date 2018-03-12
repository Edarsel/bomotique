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

<!-- FORMULAIRE DES PARAMETRES DE CONNEXION-->
<?php
require('Vue\administration\moduleParamConnexion.php');
?>

<br>

<!-- FORMULAIRE DES PARAMETRES DE LA LED-->
<?php
require('Vue\administration\moduleParamLED.php');
?>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';
