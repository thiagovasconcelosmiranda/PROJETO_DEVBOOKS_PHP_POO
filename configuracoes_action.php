<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$userDao = new UserDaoMysql($pdo);
$id = filter_input(INPUT_POST, 'id_user');
$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate');
$city = filter_input(INPUT_POST, 'city');
$work = filter_input(INPUT_POST, 'work');
$password = filter_input(INPUT_POST, 'password');
$password_confirmation = filter_input(INPUT_POST, 'password_confirmation');



if($id && $name && $email){
     $userInfo->id = $id;
     $userInfo->name = $name;
     $userInfo->city = $city;
     $userInfo->work = $work;

    //Email
    if($userInfo->email != $email){
       if($userDao->findBymail($email)===false){
         $userInfo->email = $email;
       }else{
        $_SESSION['flash']= "E-mail já existe!";
        header("Location: ".$base."/configuracoes.php");
        exit;
       }
    }
   //Birthdate
    $birthdate = explode('/' , $birthdate);
    if(count($birthdate) != 3){
        $_SESSION['flash'] = 'Data de nascimento invalida.';
        header('Location:'.$base."/configuracoes.php");
        exit;
    }
    
    $birthdate = $birthdate[2]. '-'.$birthdate[1].'-'.$birthdate[0];
     
    if(strtotime($birthdate) === false){
        $_SESSION['flash'] = 'Data de nascimento invalida.';
        header('Location:'.$base."/configuracoes.php");
        exit;
    }
    $userInfo->birthdate = $birthdate;
   

    if(!empty($password)){
       if($password === $password_confirmation){
         $hash = password_hash($password, PASSWORD_DEFAULT);
         $userInfo->password = $hash;
        
       }else{
         $_SESSION['flash'] = "As senhas não batem";
         header("Location: ".$base."/configuracoes.php");
         exit;
       }
    }


    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])){
      $newAvatar = $_FILES['avatar'];
     

      if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
            $avatarWidth = 200;
            $avatarHeight = 200;
            
            
            list($widthOrig, $heightOrig) = getimagesize($newAvatar['tmp_name']);
            $ratio = $widthOrig / $heightOrig;

            $newWidth = $avatarWidth;
            $newHeight = $newWidth / $ratio;
            
            if($newHeight < $avatarHeight){
                $newHeight = $avatarHeight;
                $newWidth = $newHeight * $ratio;
            }

            $x = $avatarWidth - $newWidth;
            $y = $avatarHeight - $newHeight;

            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;


            $finalImage = imagecreatetruecolor($avatarWidth, $avatarHeight);

            switch ($newAvatar['type']) {
              case 'image/jpeg':
              case 'image/jpg';
                $image = imagecreatefromjpeg($newAvatar['tmp_name']);
              break;

              case 'image/png';
              $image = imagecreatefrompng($newAvatar['tmp_name']);
              break;
            }
            imagecopyresampled(
              $finalImage ,$image,
              $x, $y, 0, 0,
              $newWidth, $newHeight, $widthOrig, $heightOrig
            );
            $avatarName = md5(time().rand(0,9999)).'.jpg';

            imagejpeg($finalImage, './assets/media/avatars/'.$avatarName, 100);

            $userInfo->avatar = $avatarName;
      }
    }

    //Cover
    if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])){
      $newCover = $_FILES['cover'];
     

      if(in_array($newCover['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
            $coverWidth = 850;
            $coverHeight = 313;
            
            
            list($widthOrig, $heightOrig) = getimagesize($newCover['tmp_name']);
            $ratio = $widthOrig / $heightOrig;

            $newWidth = $coverWidth;
            $newHeight = $newWidth / $ratio;
            
            if($newHeight < $coverHeight){
                $newHeight = $coverHeight;
                $newWidth = $newHeight * $ratio;
            }

            $x = $coverWidth - $newWidth;
            $y = $coverHeight - $newHeight;

            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;


            $finalImage = imagecreatetruecolor($coverWidth, $coverHeight);

            switch ($newCover['type']) {
              case 'image/jpeg':
              case 'image/jpg';
                $image = imagecreatefromjpeg($newCover['tmp_name']);
              break;

              case 'image/png';
              $image = imagecreatefrompng($newCover['tmp_name']);
              break;
            }
            imagecopyresampled(
              $finalImage, $image,
              $x, $y, 0, 0,
              $newWidth, $newHeight, $widthOrig, $heightOrig
            );
            $coverName = md5(time().rand(0,9999)).'.jpg';

            imagejpeg($finalImage, './assets/media/covers/'.$coverName, 100);

            $userInfo->cover = $coverName;
      }
    }

    $userDao->update($userInfo);

   
}
$_SESSION['flash'] = "Email - e/ou senha errados";
header("Location: ".$base."/configuracoes.php");
exit;
