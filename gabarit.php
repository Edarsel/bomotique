<!doctype html>

<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="favicon.png" />

  <!--POLICE D'ECRITURE-->
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

  <!-- Titre -->
  <title id="titrePage" >Bomotique - Connexion</title>

  <!-- JQUERY / JQUERY-UI-->
  <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- RECAPTCHA -->
  <script src='https://www.google.com/recaptcha/api.js'></script>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

  <!-- Bootstrap -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Script DataTables (tri des tableaux) https://datatables.net/examples/styling/bootstrap4.html -->
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" />

  <!-- Script controleur -->
  <script src="Script/controleur.js" defer></script>

  <!-- Feuille de style CSS -->
  <link rel="stylesheet" href="style.css" />

<!-- <script type="text/javascript" src="Script/swfobject-2.2.min.js" defer></script>
  <script type="text/javascript" src="Script/evercookie.js" defer></script>
  <script type="text/javascript" src="Script/identification.js" defer></script> -->

</head>
<body>
  <div id="global">
    <header>
      <!--<a href="" style="text-decoration:none"><h1 id="titreBlog">Bomotique v1.0</h1></a>-->

      <nav class="navbar navbar-expand-md fixed-top navbar-dark barre-navigation" id="barreNavigation">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-content" aria-controls="nav-content" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
          <img src="favicon.png" width="30" height="30" class="d-inline-block align-top" alt="">
          Bomotique
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="nav-content">

</div>
</nav>

</header>
<div id="contenu" class="card" >
  <h2 class="card-header" id="titreContenu">Titre Page</h2>
  <div class="card-body">
    <?= $contenu ?>   <!-- Élément spécifique -->
  </div>
</div>
<script>
$('#contenu').hide();
$('#contenu').show("fade", 500);
</script>
<footer id="piedBlog" class="fixed-bottom container-fluid">
  Antoine Lestrade - CPLN - 2017
</footer>
</div> <!-- #global -->
</body>
</html>
