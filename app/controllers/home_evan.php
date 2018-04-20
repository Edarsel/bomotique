<?php
//require_once('../core/Controller.php');
class Home extends Controller
 {
     public function index($name='')
     {
        /* echo 'home/index<br />';
         echo $name;*/
         $this->view('template/header');
         $this->view('home/index');
         $this->view('template/footer');

     }


 }
