<?php
ob_start();
?>
<script type="text/javascript">
$('#titrePage').text("Bomotique - Accueil");
$('#titreContenu').text("Accueil");

var contenuNavbar=$(`
  <ul class="navbar-nav">
  <li class="nav-item">
  <a class="nav-link" href="index.php?controleur=User&action=deconnexion">Déconnexion</a>
  </li>
  </ul>`);
  $('#nav-content').html(contenuNavbar);
</script>


<?php if ($_SESSION['modeConnexion']){ ?>
  <label><?= "Utilisateur connecté" ?></label>
<?php }else{ ?>
  <label><?= "Utilisateur connecté : " . strtoupper($_SESSION['UtilisateurConnecte']->nomUtilisateur) ?></label>
<?php } ?>

<br><br>

<!-- Rounded switch Source : https://www.w3schools.com/howto/howto_css_switch.asp -->
<label for="cbxOnOff">État de la LED :</label><br>
<label class="switch" for="cbxOnOff" id="lblOnOff">
  <input type="checkbox" id="cbxOnOff" <?= ($_SESSION['LED']) ? "checked" : "" ?> onchange="OnOffLED();">
  <span class="slider round"></span>
</label>

<br><br>

<button type="button" id="btnImpulsion" class="btn btn-primary">Impulsion</button>

<br><br>


<?php
$contenu = ob_get_clean();
require_once 'gabarit.php';
