<?php


function isLoggedIn(){
    if(isset($_SESSION['userID'])&&$_SESSION['LoggedIn']==true)
    {
        return true;
    }
    else{
        return false;
    }
}

function initialiserBD() {
  //$controleurUser = new controleurUser();

  $strMdp="Pass1234";
  $strMdp= password_hash($strMdp,PASSWORD_BCRYPT);
  //var_dump(initDB($strMdp));
  initDB($strMdp);
}

class Controller
{
    protected function model($model){
        require_once 'app/models/'.$model.'.php';
        return new $model();
    }

    protected function View($view , $data = []){
        require_once 'app/views/'.$view.'.php';
    }

}
