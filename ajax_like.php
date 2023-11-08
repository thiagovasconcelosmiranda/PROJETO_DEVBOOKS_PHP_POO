<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostLikeDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id');

if(!empty($id)){
  $postlikDao = new  PostLikeDaoMysql($pdo);
  $postlikDao->likeToggle($id, $userInfo->id);

}