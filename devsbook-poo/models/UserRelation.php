<?php

class UserRelation {
    public $id;
    public $user_from;
    public $user_to;
}

interface UserRalationDao {
   
    public function insertRelation(UserRelation $u);
    public function getRelationsFrom($id);
    public function getFollowing($id);
    public function getFollowers($id);

}