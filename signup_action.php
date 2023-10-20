<?php
require 'config.php';
require 'models/Auth.php';

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate');
$password = filter_input(INPUT_POST, 'password');
$rep_password = filter_input(INPUT_POST, 'rep_password');

if($email && $password && $birthdate && $rep_password ){
    $auth = new Auth($pdo, $base);

    $birthdate = explode('/' , $birthdate);
    if(count($birthdate) != 3){
        $_SESSION['flash'] = 'Data de nascimento invalida.';
        header('Location:'.$base."/signup.php.php");
        exit;
    }
    
    $birthdate = $birthdate[2]. '-'.$birthdate[1].'-'.$birthdate[0];
     
    if(strtotime($birthdate) === false){
        $_SESSION['flash'] = 'Data de nascimento invalida.';
        header('Location:'.$base."/signup.php.php");
        exit;
    }

 

    if($auth->emailExists($email) === false){
        
        if($password === $rep_password){
            $auth->registerUser($name, $email, $birthdate, $password);
            header('Location:'.$base);
            exit; 
        }else{
            $_SESSION['flash'] = 'Senhas não confere';
            header('Location:'.$base."/signup.php");
            exit; 
        }
        
       
    }else{
       echo 'Não pode cadastrar';
        $_SESSION['flash'] = 'E-mail já cadastrado';
        header('Location:'.$base."/signup.php");
        exit; 
    }

}else{
    
    $_SESSION['flash'] = 'Espaço em branco';
    header('Location:'.$base."/signup.php");
    exit;
}

