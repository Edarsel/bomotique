<?php
//require_once('../core/Controller.php');
class PublicF extends Controller
 {
     public function index($path=[])
     {
        /* echo 'home/index<br />';
         echo $name;*/
         echo getcwd();
             if($path)
             {

                 //echo '<img src="'.getcwd().'/public/img/profile/0.jpg"/>';
             }else {
                 echo 'aucun fichier sélectionné';
                  echo '<img src="/modelemvcclass/public/img/profile/0.jpg"/>';
             }


     }


 }
