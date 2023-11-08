<?php

class PostComment {
     public $id;
     public $id_user;
     public $type;
     public $created_at;
     public $body;
}

interface PostCommentDAO{
    public function getComments($id_post);
    public function addComments(PostComment $pc);
   
}
