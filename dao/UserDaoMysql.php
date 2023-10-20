<?php 
require 'models/User.php';
require 'dao/UserRelationDaoMysql.php';

class UserDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PDO $pdo){
       $this->pdo = $pdo;
    }

    private function generateUser($array, $full = false){
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->email = $array['email'] ?? 0;
        $u->name = $array['name'] ?? 0;
        $u->birhdate = $array['email'] ?? 0;
        $u->password = $array['password'] ?? 0;
        $u->city = $array['city'] ?? 0;
        $u->work = $array['work'] ?? 0;
        $u->avatar = $array['avatar'] ?? 0;
        $u->cover = $array['cover'] ?? 0;
        $u->token = $array['token'] ?? 0;
        
        if($full){
          $urDaoMysql = new UserRelationDaoMysql($this->pdo);

          //Followers = Quem segue o úsuario
          $u->followers = $urDaoMysql->getFollowers($u->id);
           
          //Followers = Quem o usuário segue
          $u->following = $urDaoMysql->getFollowing($u->id);

          //Fotos
          $u->photos = [];

        }
        return $u;
    }

    public function finByToken($token){
        if(!empty($token)){
           $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
           $sql->bindValue(':token', $token);
           $sql->execute();

           if($sql->rowCount() > 0){
               $data = $sql->fetch(PDO::FETCH_ASSOC);
               $user = $this->generateUser($data);
               return $user;
           }
        }

        return false;
    }

    public function findById($id, $full = false){
        if(!empty($id)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
            if($sql->rowCount() > 0){
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data, $full);

              
                return $user;
            }
         }
 
         return false;
    }

    public function findBymail($email){

            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindValue(':email', $email);
            $sql->execute();
 
            if($sql->rowCount() > 0){
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
         
 
        return false;
    }
     
    public function update(User $u){

        if(!empty($u->token)){
            

             $sql = $this->pdo->prepare('UPDATE users SET
               email = :email,
               name = :name,
               birthdate = :birthdate,
               city = :city,
               work = :work,
               avatar = :avatar,
               cover = :cover,
               token = :token
               WHERE id = :id'
               );

            $sql->bindValue(':email', $u->email);
            $sql->bindValue(':name', $u->name);
            $sql->bindValue(':birthdate', $u->birhdate);
            $sql->bindValue(':city', $u->city);
            $sql->bindValue(':work', $u->work);
            $sql->bindValue(':avatar', $u->avatar);
            $sql->bindValue(':cover', $u->cover);
            $sql->bindValue(':token', $u->token);
            $sql->bindValue(':id', $u->id);
            $sql->execute();
           
            if($sql->rowCount()){
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
               return $user;
            }
        }

        return false;
    }

    public function insert( User $u){
        $sql = $this->pdo->prepare("INSERT INTO users (
           name, email, birthdate , password, token
        ) VALUES (
          :name, :email, :birthdate , :password, :token
        )");

        $sql->bindValue(':name', $u->name);
        $sql->bindValue(':email', $u->email);
        $sql->bindValue(':birthdate', $u->birthdate);
        $sql->bindValue(':password', $u->password);
        $sql->bindValue(':token', $u->token);
        $sql->execute();

        return true;
    }

    

}
