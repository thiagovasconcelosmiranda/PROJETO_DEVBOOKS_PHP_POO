<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMysql.php';
require_once 'dao/PostCommentDaoMysql.php';

class PostDaoMysql implements PostDAO
{
   private $pdo;

   public function __construct(PDO $driver)
   {
      $this->pdo = $driver;
   }


   public function insert(Post $p)
   {
      $sql = $this->pdo->prepare('INSERT INTO posts (
            id_user, type, created_at, body
         ) VALUES 
         (
            :id_user, :type, :created_at, :body
        )');

      $sql->bindValue(':id_user', $p->id_user);
      $sql->bindValue(':type', $p->type);
      $sql->bindValue(':created_at', $p->created_at);
      $sql->bindValue(':body', $p->body);
      $sql->execute();

   }

   public function delete($id, $id_user)
   {
      $postLikeDao = new PostLikeDaoMysql($this->pdo);
      $postCommentDao = new PostCommentDaoMysql($this->pdo);

      //1. verificar se o post existe
      $sql = $this->pdo->prepare('SELECT * FROM posts
    WHERE id = :id AND id_user = :id_user');
      $sql->bindValue(':id', $id);
      $sql->bindValue(':id_user', $id_user);
      $sql->execute();

      if ($sql->rowCount() > 0) {
         $post = $sql->fetch(PDO::FETCH_ASSOC);

         // 2. deletar os likes e comments
         $postLikeDao->deleteFromPost($id);
         $postCommentDao->deleteFromPost($id);

         //3. deletar os eventual fotos (type == photo)
         if ($post['type'] === 'photo') {

            $img = 'assets/media/uploads/' . $post['body'];
            if (file_exists($img)) {
               unlink($img);
            }
         }

         //4. deletar post
         $sql = $this->pdo->prepare('DELETE FROM posts
         WHERE id = :id AND id_user = :id_user');
         $sql->bindValue(':id', $id);
         $sql->bindValue(':id_user', $id_user);
         $sql->execute();
      }
   }


   public function getUserFeed($id_user, $page = 1)
   {
      $array = ['feed' =>[]];
      $parPage = 3;

      $offset = ($page - 1) * $parPage;

      $sql = $this->pdo->prepare("SELECT * from posts 
      WHERE id_user = :id_user
      ORDER BY created_at DESC LIMIT $offset, $parPage");
      $sql->bindValue(':id_user', $id_user);
      $sql->execute();

      if ($sql->rowCount() > 0) {
         $data = $sql->fetchAll(PDO::FETCH_ASSOC);
         $array['feed'] = $this->_postListToObject($data, $id_user);
      }

       $sql = $this->pdo->prepare("SELECT COUNT(*) as c from posts 
      WHERE id_user = :id_user");
      $sql->bindValue(':id_user', $id_user);
      $sql->execute();
      
      $totalData= $sql->fetch();
      $total = $totalData['c'];

      $array['pages'] = ceil($total / $parPage);
      $array['currentPage'] = $page;

      return $array;
   }

   public function getHomeFeed($id_user, $page = 1)
   {
      $array = [];

      $parPage = 3;
     
      $offset = ($page - 1) * $parPage;

      // 1. lista usuários que eu sigo
      $urDao = new UserRelationDaoMysql($this->pdo);
      $userList = $urDao->getFollowing($id_user);
      $userList[] = $id_user;

      // 2. Pegar os posts ordernado pela data
      $sql = $this->pdo->query("SELECT * from posts 
        WHERE id_user IN (" . implode(',', $userList) . ")
       ORDER BY created_at DESC LIMIT $offset, $parPage");

      if ($sql->rowCount() > 0) {
         $data = $sql->fetchAll(PDO::FETCH_ASSOC);
         // 3. Transformar o resultado em objetos
         $array['feed'] = $this->_postListToObject($data, $id_user);
      }
      // 4. Pegar o total de posts
      $sql = $this->pdo->query("SELECT COUNT(*) as c from posts 
      WHERE id_user IN (" . implode(',', $userList) . ")");
      $totalData = $sql->fetch();
      $total = $totalData['c'];
      $array['pages'] = ceil($total / $parPage);

      $array['currentPage'] = $page;

      return $array;
   }

   public function getPhotosFrom($id_user)
   {
      $array = [];

      $sql = $this->pdo->prepare("SELECT * FROM posts
       WHERE id_user = :id_user AND type = 'photo'
       ORDER BY created_at DESC");
      $sql->bindValue(':id_user', $id_user);
      $sql->execute();

      if ($sql->rowCount() > 0) {
         $data = $sql->fetchAll(PDO::FETCH_ASSOC);
         $array = $this->_postListToObject($data, $id_user);
      }

      return $array;
   }

   private function _postListToObject($postList, $id_user)
   {
      $posts = [];
      $userDao = new UserDaoMysql($this->pdo);
      $postLikeDao = new PostLikeDaoMysql($this->pdo);
      $postCommentDao = new PostCommentDaoMysql($this->pdo);


      foreach ($postList as $post_item) {
         $newPost = new Post();
         $newPost->id = $post_item['id'];
         $newPost->type = $post_item['type'];
         $newPost->created_at = $post_item['created_at'];
         $newPost->body = $post_item['body'];
         $newPost->mine = false;

         if ($post_item['id_user'] == $id_user) {
            $newPost->mine = true;
         }

         //pegar informações d usuário
         $newPost->user = $userDao->findById($post_item['id_user']);

         // Informações sobre LIKE
         $newPost->likeCount = $postLikeDao->getLikeCount($newPost->id);
         $newPost->liked = $postLikeDao->isLiked($newPost->id, $id_user);

         //informações sobre COMMENTS
         $newPost->comments = $postCommentDao->getComments($newPost->id);
         $posts[] = $newPost;
      }

      return $posts;
   }
}