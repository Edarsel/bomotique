<?php
ob_start();
//echo $_COOKIE["identification"];
?>
<script type="text/javascript">
    $('#titrePage').text("Bomotique - Connexion")
    $('#titreContenu').text("Connexion")

    var contenuNavbar=$(`
                <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link" href="index.php?action=pageConnexion">Connexion Utilisateur</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="index.php?action=pageConnexionAdmin">Connexion Administration</a>
                </li>
                </ul>`);
    $('#nav-content').html(contenuNavbar);
</script>

<div>
    <form method="post" action="index.php" autocomplete="off">

    <fieldset class="form-group">
    <?php
if (!($_SESSION['modeConnexion'])) {?>
        <label for="pseudo">Nom d'utilisateur :</label><br>
        <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo ?>" placeholder="Ex: DupontD"/>

    <?php }?>
    </fieldset>
    <fieldset class="form-group">
        <label for="pass">Mot de passe :</label><br>
        <input type="password" name="pass" id="pass" placeholder="******" />
    </fieldset>

    <fieldset class="form-group">
        <!-- Captcha Google -->
        <div class="g-recaptcha" data-sitekey="6Le3mjkUAAAAAO8iqeRGS4LUlAGgSlhBJ574ZdPi"></div>
    </fieldset>
        <input type="hidden" name="action" id="action" value="connexion">
        <input type='submit' name="envoyer" id="envoyer" value="Se connecter" class="btn btn-primary" />

    </form>

    <a href="index.php?action=pageConnexionAdmin">Page Administration</a>

</div>
<?php
$contenu = ob_get_clean();
require 'gabarit.php';
