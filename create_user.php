<?php
require_once 'operatii_db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $db=new Database('localhost','root','','test');
    $username=$_POST['username'];
    $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
    if($db->create('users',['username'=>$username,'password'=>$password])){
        echo "User created. <a href='login.php'>Login</a>";
    } else echo "Error.";
    exit;
}
?>
<!DOCTYPE html><html><body>
<h2>Create User</h2>
<form method="POST">
<input name="username" required placeholder="Username">
<input type="password" name="password" required placeholder="Password">
<button>Create User</button>
</form>
</body></html>
