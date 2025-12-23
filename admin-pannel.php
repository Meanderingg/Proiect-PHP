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
            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
</body>
</html>
