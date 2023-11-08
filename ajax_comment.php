<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostCommentDaoMysql.php';

$array = [];
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_POST, 'id');
$txt = filter_input(INPUT_POST, 'txt');

if($id && $txt){
  $comments = new PostCommentDaoMysql($pdo);

  $newComment = new PostComment();
  $newComment->id_post = $id;
  $newComment->id_user = $userInfo->id;
  $newComment->body = $txt;
  $newComment->create_at = date('Y-m-d H:i:s');

   $comments->addComments($newComment);
   $array = [
    'error' => '',
    'link' => $base.'/perfil.php?id='.$userInfo->id,
    'avatar' => $base.'/assets/media/avatars/'.$userInfo->avatar,
    'name' => $userInfo->name,
    'body' => $txt
   ];
}

header('Content-Type: application/json');
echo json_encode($array);
exit;