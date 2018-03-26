<?php

//SOURCE : http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/
// https://apprendre-php.com/tutoriels/tutoriel-45-singleton-instance-unique-d-une-classe.html

//Le constructeur est privé pour éviter de déclarer un objet du type Bd
//On contruira les requêtes SQL avec Bd::getInstanceConnexion()

  class Bd {
    private static $instanceConnexion = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getBdd() {
      if (!isset(self::$instanceConnexion)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$instanceConnexion = new PDO('mysql:host=localhost;dbname=php_mvc', 'root', '', $pdo_options);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          //$bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', '');
          self::$instanceConnexion = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', '', $pdo_options);
        } else {
          //$bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', 'Pass1234');
          self::$instanceConnexion = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', 'Pass1234', $pdo_options);
        }
      }

      return self::$instanceConnexion;
    }
  }
