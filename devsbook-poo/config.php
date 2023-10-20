<?php 
session_start();
$base = 'http://localhost/devsbook-poo';
try {
  $db_name = 'devsbook';
  $db_host = 'localhost';
  $db_user = 'root';
  $db_pass = '';

$pdo = new PDO("mysql:dbname=".$db_name.";host=".$db_host, $db_user, $db_pass);
} catch (Exception $e) {
    die('Erro: Banco Não corectado '. $e);
}
