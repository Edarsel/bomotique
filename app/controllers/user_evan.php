<?php
//require_once('../core/Controller.php');

class user extends Controller
 {
     /*------------------------------PAGES------------------------------------------*/
     public function index()
     {
         if(isLoggedIn())
         {
             header('Location: /modelemvcclass/user/profile');
         }else {
             if(isset($_POST['emailLogin'])&&isset($_POST['passwordLogin']))
             {
                 $user = $this->model('UserModel');
                 $var = $user->LoginModel($_POST['passwordLogin'], $_POST['emailLogin']);
                 if(!empty($var))
                 {
                     $_SESSION['LoggedIn'] = true ;
                     $_SESSION['userID'] = $var;
                     header('Location: /modelemvcclass/user/profile');
                 }
             }
             //On affiche la page
             $this->view('template/header');
             $this->view('user/login');
             $this->view('template/footer');
         }

     }


     public function logout(){
         session_unset();
         header('Location: /modelemvcclass/user/index');
     }

     public function profile(){
         if(isLoggedIn())
         {
             //On affiche la page
                $user = $this->model('UserModel');
                $userData = $user->getById($_SESSION['userID']);
                $this->view('template/header');
                $this->view('user/profile',$userData);
                $this->view('template/footer');

         }else {
             header('Location: /modelemvcclass/user/index');
         }
     }

     public function EditUserInfo(){
         $userData = [];
         $user = $this->model('UserModel');
         if(isLoggedIn())
         {
             if(isset($_POST['formPass'])){
                 //TODO : modifier checkmail pour qu'il n'y ai pas de problème lors d'un non chagement de mail
                 if(!CheckMailChange()&&!empty($_POST['pseudo'])&&!empty($_POST['name'])&&!empty($_POST['surname'])&&!empty($_POST['dateNaissance'])){
                     //Tout a fonctionné, on enregistre puis on redirige
                     $user->EditUser($_POST['email'], $_POST['name'],$_POST['surname'],$_POST['pseudo'],$_POST['dateNaissance'],$_SESSION['userID']);
                     header('Location: /modelemvcclass/user/profile');
                 }else{
                     // Il y a des erreur
                     $FieldData = array('email'=>$_POST['email'],'pseudo'=>$_POST['pseudo'],'Nom'=>$_POST['name'],'Prenom'=>$_POST['surname'],'dateNaissance'=>$_POST['dateNaissance']);
                     array_push($userData,$FieldData);
                 }
             }else{

                 $userData = $user->getById($_SESSION['userID']);
             }
                 //On affiche la page
                $this->view('template/header');
                $this->view('user/editUserInfo',$userData);
                $this->view('template/footer');

         }else {
             header('Location: /modelemvcclass/user/index');
         }
     }

     public function EditUserPicture(){
         if(isLoggedIn())
         {
              $user = $this->model('UserModel');
             if(handleErrorPicture()){
                 $path = uploadPicture($_FILES['profile']);
                 $user->EditPictureUser($path,$_SESSION['userID']);
                 header('Location: /modelemvcclass/user/profile');
             }else{
                 $userData = $user->getById($_SESSION['userID']);
                 $this->view('template/header');
                 $this->view('user/EditUserPicture',$userData[0]['picturePath']);
                 $this->view('template/footer');
             }

        }else {
           header('Location: /modelemvcclass/user/profile');
       }
     }
     public function EditUserPassword(){
         if(isLoggedIn())
         {
             if(!empty($_POST['password1'])&&!empty($_POST['password2']))
             {
                 if($_POST['password1']===$_POST['password2'])
                 {
                     //On push le nouveau mot de passe
                 }
             }
             $this->view('template/header');
             $this->view('user/EditUserPassword');
             $this->view('template/footer');
         }else {
            header('Location: /modelemvcclass/user/profile');
        }
     }

     public function ProfileUser($id){
         if(isLoggedIn())
         {
             if(isset($id))
             {     $user = $this->model('UserModel');
                   $userData = $user->getById($id);
                   if($userData){
                       $this->view('template/header');
                       $this->view('user/profileUser',$userData);
                       $this->view('template/footer');
                   }else{
                       //l'utilisateur n'existe pas en BDD
                   }
             }else {
                    //Si aucun id header est spécifié, on redirige vers la page de l'utilisateur connecté
                    header('Location: /modelemvcclass/user/profile');
             }

         }else {
             header('Location: /modelemvcclass/user/index');
         }
     }
     public function register()
     {
         if(isLoggedIn())
         {
             header('Location: /modelemvcclass/user/profile');
         }else{
             if($this->checkRegisterField()&&$_POST['password1']===$_POST['password2']&&$this->handleErrorPicture())
             {
                 //on set les variables de session et on enregistrer l'utilisateur
                 $pictPath = 'public/img/profile/0.jpg';
                 if(isset($_FILES['profile'])){
                     $pictPath = $this->uploadPicture($_FILES['profile']);
                 }
                 $user = $this->model('UserModel');
                 $user->AddUser($_POST['name'],$_POST['surname'],$_POST['dateNaissance'],$_POST['email'],$_POST['password1'],$_POST['pseudo'],$pictPath);
                 header('Location: /modelemvcclass/user/index');
             }else{
                  //On affiche la page formPass
                 $this->view('template/header');
                 if(isset($_POST['formPass']))
                 {

                     $errors = $this->errorShowList();
                     //print_r($errors);
                     $this->view('template/ErrorShow',$errors);
                 }
                 $this->view('user/register');
                 $this->view('template/footer');
             }
         }

     }

     /*------------------------------>Vérifications mot de passe, mail , ...----------------------------------------------*/
     private function CheckMailChange()
     {
         $user = $this->model('UserModel');
         $userData = $user->getById($_SESSION['userID']);
         if(!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
             //vérifie l'addrese mail actuelle
             if($_POST['email']==$userData[0]['email']){
                 return true;
             }else if(!$user->Exists($_POST['email'])){
                 return true;
             }else {

             }
         }else {
             return false;
         }
     }

    private function checkSetOrEmptyField($arrID)
    {
        foreach ($arrID as $key => $value) {
            if(!isset($value)||empty($value))
            {
                return true;
            }
        }
        return false;
    }

    private function checkMail()
    {
        $user = $this->model('UserModel');
        if(isset($_POST['email'])&&!$user->Exists($_POST['email'])&&!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            return true;
        }else {
            return false;
        }
    }

    private function checkRegisterField()
    {
        if($this->checkMail()&&isset($_POST['password1'])&&isset($_POST['password2'])&&isset($_POST['pseudo'])&&isset($_POST['dateNaissance'])&&isset($_POST['name'])&&isset($_POST['surname'])
        &&!empty($_POST['password1'])&&!empty($_POST['password2'])&&!empty($_POST['pseudo'])&&!empty($_POST['dateNaissance'])&&!empty($_POST['name'])&&!empty($_POST['surname']))
        {
            return true;
        }else {
            return false;
        }
    }

    private function errorShowList()
    {
        $errorList = array();

        if(empty($_POST['password1'])){
            array_push($errorList,'Veillez renseigner le mot de passe') ;
        }
        if(empty($_POST['password2'])||$_POST['password1']!==$_POST['password2']){
            array_push($errorList,'Les mots de passe ne sont pas les même') ;
        }
        if(empty($_POST['pseudo'])){
            array_push($errorList,'Veuillez renseigner le pseudo') ;
        }
        if(empty($_POST['dateNaissance'])){
            array_push($errorList,'Veuillez renseigner la date de naissance') ;
        }
        if(empty($_POST['name'])){
            array_push($errorList,'Veuillez renseigner le nom') ;
        }
        if(empty($_POST['surname'])){
            array_push($errorList,'Veuillez renseigner le prénom') ;
        }
        if(!$this->checkMail()){
            array_push($errorList,'Cette addresse mail est déjà enregistrée') ;
        }
        return $errorList;
    }

    private function checkPictureType($picture){
        if($picture['type']=='image/gif' || $picture['type']=='image/png' || $picture['type']=='image/jpeg'&&$picture['error'] == 0){
            return true;
        }else {
            return false;
        }
    }

    private function uploadPicture($picture){
             move_uploaded_file($picture['tmp_name'], 'public/img/profile/' . uniqid().'.'.$picture['name']['extension']);
             return 'public/img/profile/' . uniqid().'.'.$picture['name']['extension'];
    }

    private function handleErrorPicture()
    {
        if(isset($_FILES['profile']))
        {
            if(checkPictureType($_FILES['profile'])){
                return true;
            }else {
                return false;
            }
        }else {
            return true;
        }
    }


 }
