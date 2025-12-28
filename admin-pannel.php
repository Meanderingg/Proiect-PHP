<!DOCTYPE html>
<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './Database.php';
include './operatii_db.php';

if(! isset($_SESSION['username'])){
    header('Location: ./login-user.php');
}
else
    if(! isset($_SESSION['admin'])){
        header('Location: ./homepage.php'); //modify for editor and author!
    }

else
try {
  // get all the users
    $record = OperatiiDB::read('users', 'WHERE 1 = 1'); //success, asa apelezi functia
    //var_dump($record);


} catch (PDOException $e) {
    die(" Connection failed: " . $e->getMessage());
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homepage</title>
</head>

<body>
<header>
<nav>
<a href="homepage.php">Home</a> |
<a href="login-user.php">Login</a> |
<!--modify nav bar based on which user uses it-->
</nav>
</header>

    <h1>SUCCESS</h1>
    <table border='1'>
        <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Author ID</th>
            <th>Admin ID</th>
            <th>Editor ID</th>
            <th>email</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($record as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['user_id']) ?></td>
                <td><?php echo htmlspecialchars($record['username']??' ') ?></td>
                <td><?php echo htmlspecialchars($record['author_id']??' ') ?></td> <!--??' ' daca e null il transf in ' '-->
                <td><?php echo htmlspecialchars($record['administrator_id']??' ') ?></td>
                <td><?php echo htmlspecialchars($record['editor_id']??' ') ?></td>
                <td><?php echo htmlspecialchars($record['email']??' ') ?></td>
            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
        </tbody>
        </table>

    <h2>Operatii utilizatori</h2>
    <h4>Stergere</h4>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="delete_user"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="id">ID utilizator</label><br>
        <input type="text" name="id" id="id">
        <input type="submit" value="Submit">
        </form>
    <h4>Modificare</h4>
        <p>Blank pentru valoarea default, ID este necesar</p>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="alter_user"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="id">ID utilizator</label><br>
        <input type="text" name="id" id="id"><br>

        <label for="e-mail">e-mail</label><br>
        <input type="text" name="admin_id" id="admin_id"><br>

        <label for="admin_id">ID Administrator</label><br>
        <input type="text" name="admin_id" id="admin_id"><br>

        <label for="editor_id">ID Editor</label><br>
        <input type="text" name="editor_id" id="editor_id"><br>

        <label for="author_id">ID Autor</label><br>
        <input type="text" name="author_id" id="author_id"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username">

        <input type="submit" value="Submit">
        </form>

    <h4>Adaugare</h4>
        <p>Blank pentru valoarea default</p>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="create_user"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="email">e-mail</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="admin_id">ID Administrator</label><br>
        <input type="text" name="admin_id" id="admin_id"><br>

        <label for="editor_id">ID Editor</label><br>
        <input type="text" name="editor_id" id="editor_id"><br>

        <label for="author_id">ID Autor</label><br>
        <input type="text" name="author_id" id="author_id"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="password">Password</label><br>
        <input type="text" name="password" id="password">

        <input type="submit" value="Submit">
        </form>
<?php
    require_once './Database.php';
    require_once './operatii_db.php';
    //var_dump($_POST);
    if(isset($_POST['action']))
    {
        if($_POST['action'] == "delete_user")
        {
            //delete($tabel,$conditie,$param); 
         try {
            $param = ['id' => $_POST['id']];
            //var_dump($param);
            OperatiiDB::delete('users', 'user_id = :id', $param); //stergere de utilizator
            } catch (PDOException $e) {
                die(" Connection failed: " . $e->getMessage());
            }
        }
        elseif($_POST['action'] == "create_user")
        {
            //var_dump($_POST);
            //public static function update($tabel, $valori, $conditie){
         try {
             $param = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
             ];
             if($_POST['admin_id'] != ''){
                 $param['administrator_id'] = $_POST['admin_id'];
             }

             if($_POST['editor_id'] != ''){
                 $param['editor_id'] = $_POST['editor_id'];
             }

             if($_POST['author_id'] != ''){
                 $param['author_id'] = $_POST['editor_id'];
             }
            //var_dump($param);
            OperatiiDB::create('users', $param); //creare de utilizator
            } catch (PDOException $e) {
                die(" Connection failed: " . $e->getMessage());
            }
        }

    }
?>
</body>
</html>
