<?php

require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/models/connexionBD.php';
require_once 'app/models/AppModel.php';

date_default_timezone_set('Europe/Paris');

$_SESSION['LED'] = (int) exec('cat /sys/class/gpio/gpio68/value');
$appModel = new AppModel;
$_SESSION['modeConnexion'] = $appModel->getModeConnexion();
