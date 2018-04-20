<?php

class App
{

    protected $controller = 'user'; //Controlleur par défaut

    protected $method = 'index'; //Page des classes controlleur par défaut

    protected $params = [];  //Paramètres que l'on passe à l'url

    public function __construct(){
        try{
            $url = $this->parseUrl();

            if(file_exists(getcwd().'/app/controllers/'.$url[0].'.php')){

                $this->controller = $url[0];
                unset($url[0]);
            }
            //On appelle le controlleur
            require_once 'app/controllers/'.$this->controller.'.php';
            //On crée un objet controlleur pour véréfier ensuite que la méthode existe
            $this->controller = new $this->controller;
            //On vérifie que la méthode existe
            if(isset($url[1]))
            {
                if(method_exists($this->controller, $url[1])){
                    $this->method =  $url[1];
                    unset($url[1]);
                }
            }
            // On vérifie que ce qu'il reste dans l'url ne soit pas vide si c'est le cas on lui donne les params
            $this->params = $url ? array_values($url) : [];

            //On apelle la méthode de la classe constructeur correspondant et on lui passe des paramètres
            call_user_func_array([$this->controller,$this->method] , $this->params);

        }catch(Exception $e) {
                echo 'Erreur : ' . $e->getMessage();
            }

    }

    //Permet de récupérer les paramètres passés dans l'url
    public function parseUrl(){
        if(isset($_GET['url'])){
            //Va 1. retirer le / de fin si il y en a un, 2. Nettoyer l'url en gardant que certain charactèr, 3. Séparer dans un tableau à chaques /
            return $url =explode('/',filter_var(rtrim(($_GET['url']),'/'),FILTER_SANITIZE_URL));
        }
    }
}
