<?php
ob_start();

require('Vue\administration\menuAdministration.php');
?>
<script type="text/javascript">
$('#titrePage').text("Bomotique - Logs de connexion");
$('#titreContenu').text("Logs de connexion");
</script>

<div id="divTableauLogsConnexion">
<table id="tableauLogsConnexion" class="table table table-bordered table-hover table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">IP client</th>
      <th scope="col">Date/Heure</th>
      <th scope="col">Connexion réussie</th>
      <th scope="col">Administrateur</th>
      <th scope="col">Compte utilisateur</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach ($listeLogs as $clef => $log) {
        echo "<tr scope='row'>";
        echo "<td>".$log->numero."</td>";
        echo "<td>".$log->empreinteClient."</td>";
        echo "<td>".$log->dateHeure."</td>";
        echo "<td>".($log->connexionReussie == 1 ? "OUI" : "NON")."</td>";
        echo "<td>".($log->estAdministrateur == 1 ? "OUI" : "NON")."</td>";
        echo "<td>".$log->nomUtilisateur."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
</div>

<br>
<a href="index.php?action=pageAdministration">Page Administration</a>

<?php
$contenu = ob_get_clean();
require 'gabarit.php';
