<?php
require 'config.php';
require 'models/Auth.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');

if($email && $password){
    $auth = new Auth($pdo, $base);
     
     if($auth->validateLogin($email, $password)){
        echo 'RECEBEU';
        header('Location: '.$base);
        exit;
    } 
}

$_SESSION['flash'] = 'E-mail e/ou senha erradas.';
header('Location:'.$base."/login.php");
exit;

