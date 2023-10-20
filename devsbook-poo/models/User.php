<?php

class User {
    public $id;
    public $email;
    public $password;
    public $name;
    public $birthdate;
    public $city;
    public $work;
    public $avatar;
    public $cover;
    public $token;
}

interface UserDAO {
    public function finByToken($token);
    public function findBymail($email);
    public function findById($id);
    public function update(User $u);
    public function insert(User $u);
}