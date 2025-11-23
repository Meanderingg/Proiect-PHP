<?php
error_reporting(E_ALL); ini_set('display_errors',1);
require_once 'operatii_db.php';

if($_SERVER['REQUEST_METHOD']!=='POST'){
    echo "Please log in through the form.";
    exit;
}

$db=new Database('localhost','root','','test');

$username=$db->conn->real_escape_string($_POST['username']);
$password=$_POST['password'];

$users=$db->read('users',"username='$username'");

if($users && password_verify($password,$users[0]['password'])){
    header('Location: homepage.html');
    exit;
} else {
    echo "Invalid credentials. <a href='login.php'>Try again</a> or <a href='create_user.php'>Create account</a>";
}
?>
