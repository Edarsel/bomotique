<?php

  // just a list of the controllers we have and their actions
  // we consider those "allowed" values
  //CONTROLEURS ET ACTIONS AUTORISEES A ETRE APPELER
  //Les actions autorisées sont souvent des actions envoyées par des formulaires et par les requêtes AJAX
  $controllers = array('Pages' => ['vueConnexion', 'vueConnexionAdmin', 'vuePrincipale'],
                        'User' => ['connexion', 'connexionAdmin','deconnexion']);

  // check that the requested controller and action are both allowed
  // if someone tries to access something else he will be redirected to the error action of the pages controller
  if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
      appelleControleur($controller, $action);
    } else {
      appelleControleur('Pages', 'vueConnexion');
    }
  } else {
    appelleControleur('Pages', 'vueConnexion');
  }


  function appelleControleur($controller, $action) {
    // require the file that matches the controller name
    require_once('Controleur/controleur' . $controller . '.php');

    // create a new instance of the needed controller
    switch($controller) {
      case 'Pages':
        $controller = new controleurPages();
      break;
      case 'User':
        // we need the model to query the database later in the controller
        $controller = new controleurUser();
      break;

    }

    // call the action
    $controller->{ $action }();
  }
