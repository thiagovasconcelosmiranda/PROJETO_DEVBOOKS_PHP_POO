<?php 
require 'dao/UserDaoMysql.php';

class Auth {
    private $pdo;
    private $base;
    private $dao;

    public function __construct(PDO $pdo, $base){
       $this->pdo = $pdo;
       $this->base = $base;
       $this->dao =  new UserDaoMysql($this->pdo, $this->base);
    }
   
    public function checkToken() {
       if(!empty($_SESSION['token'])){
           $token = $_SESSION['token']; 
           $user = $this->dao->findByToken($token);
           if($user){
            return $user;
           }
       }

      header("Location:".$this->base."/login.php");
      exit;
    }

    public function validateLogin($email, $password){
        $user = $this->dao->findBymail($email);
       
        if($user){
          if(password_verify($password, $user->password)){
              $token = md5(time().rand(0,9999));
              $_SESSION['token'] = $token;
              $user->token = $token;
              $this->dao->update($user);
              return true;
          }
        }
        return false;
    }

   public function emailExists($email){
      return $this->dao->findBymail($email) ? true : false;
   }
    
   public function registerUser($name, $email, $birthdate, $password){
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $token = md5(time().rand(0, 9999));
      
      $newUser = new User();
      $newUser->name = $name;
      $newUser->email = $email;
      $newUser->birthdate = $birthdate;
      $newUser->password =  $hash;
      $newUser->token = $token;

      $this->dao->insert($newUser);
      $_SESSION['token'] = $token;
   }
}