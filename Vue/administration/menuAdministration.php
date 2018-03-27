<script type="text/javascript">
var contenuNavbar=$(`
  <ul class="navbar-nav">
  <li class="nav-item">
  <a class="nav-link" href="index.php?controleur=Pages&action=vueAdministration">Page Administration</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="index.php?controleur=Pages&action=vueEditionUtil">Gestion Utilisateurs</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="index.php?controleur=Pages&action=vueLogsConnexion">Logs</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="index.php?controleur=User&action=deconnexion">Déconnexion</a>
  </li>
  </ul>`);
  $('#nav-content').html(contenuNavbar);
</script>
