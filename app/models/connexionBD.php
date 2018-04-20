<?php

//SOURCE : http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/
// https://apprendre-php.com/tutoriels/tutoriel-45-singleton-instance-unique-d-une-classe.html

//Le constructeur est privé pour éviter de déclarer un objet du type Bd
//On contruira les requêtes SQL avec Bd::getInstanceConnexion()




  class connexionBD {
    private static $instanceConnexion = NULL;

    public function __construct() {}

    private function __clone() {}

    public static function getBdd() {
      if (!isset(self::$instanceConnexion)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        //self::$instanceConnexion = new PDO('mysql:host=localhost;dbname=php_mvc', 'root', '', $pdo_options);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          //$bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', '');
          self::$instanceConnexion = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', '', $pdo_options);
        } else {
          //$bdd = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', 'Pass1234');
          self::$instanceConnexion = new PDO('mysql:host=localhost;dbname=bomotique;charset=utf8', 'root', 'Pass1234', $pdo_options);
        }
      }

      $bdd = self::$instanceConnexion;
      $sql = "SHOW DATABASES LIKE 'bomotique';";
      $stmt = $bdd->prepare($sql);
      if (is_null($stmt->execute()))
      {
        $strMdp="Pass1234";
        $strMdp= password_hash($strMdp,PASSWORD_BCRYPT);
        //var_dump(initDB($strMdp));
        initDB($strMdp);
      }

      return self::$instanceConnexion;
    }

    private function initDB($strMdp){
      $coBD = new connexionBD;

      $bdd = $coBD->getBdd();

      $sql = "CREATE DATABASE IF NOT EXISTS bomotique;";
      $bdd->exec($sql);

      $sql = file_get_contents("public/Script/initDB.sql");
      $stmt = $bdd->prepare($sql);
      $stmt->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
      $stmt->bindParam(':mdp2', $strMdp, PDO::PARAM_STR);
      return $stmt->execute();


      $bdd2 = $coBD->getBdd();
      $stmt2 = $bdd2->prepare('INSERT INTO tbl_utilisateur (numero,nomUtilisateur, motDePasse, estAdministrateur) VALUES (1,"admin", :mdp2 ,1);
        INSERT INTO tbl_application (numero, estEnModePassword, motDePasse,tempsImpulsion,nbTentative,tempsBlocage,tempsIntervaleTentative) VALUES (1, 1, :mdp, 5000, 5, `00:30:00`, `00:00:02`);');
      $stmt2->bindParam(':mdp', $strMdp, PDO::PARAM_STR);
      $stmt2->bindParam(':mdp2', $strMdp, PDO::PARAM_STR);
      //return $stmt2->execute();
    }
  }
