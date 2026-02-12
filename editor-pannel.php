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
if(! isset($_SESSION['editor'])){
        header('Location: ./homepage-editor.php'); //modify for editor and author!
        exit;
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] == "delete_article") {
            $id_to_delete = intval($_POST['id']);
            OperatiiDB::delete('articles', 'article_id = :id', ['id' => $id_to_delete]);

            header("Location: editor-pannel.php?");
            exit;
        }
    } catch (PDOException $e) {
        die("Operation failed: " . $e->getMessage());
    }
}

try {
    $record = OperatiiDB::read('articles', '*', 'WHERE 1 = 1'); //success, asa apelezi functia
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

    <h1>Editor pannel</h1>
    <h3>Aricole</h3>
    <table border='1'>
        <thead>
        <tr>
            <th>Titlu</th>
            <th>Autor</th>
            <th>Data</th>
            <th>Aprobat</th>
            <th>Link</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($record as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['title']??' ') ?></td>
                <td>
                    <?php
                        $article_author = OperatiiDB::read(
                        'users',
                        'username',
                        'WHERE author_id = ' . intval($record['author_id'])
                        );//nu e varianta optima, fac select pt fiecare autor, in loc sa am un array cu username-ul tuturor autorilor

                        echo htmlspecialchars($article_author[0]['username'] ?? 0);
                    ?>
                </td>
                <td><?php echo htmlspecialchars($record['publish_date']??' ') ?></td>
                <td><?php 
                        
                    $raw = isset($record['approved']) ? $record['approved'] : null;
                    $formatted = ((int)$raw) ? 'Da' : 'Nu';
                    echo htmlspecialchars($formatted??' ')
                ?></td>
                <td><a href="read-articles.php?id=<?= htmlspecialchars($record['article_id']) ?>"><?php echo $record['article_id']; ?></a></td>
            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
        </tbody>
        </table>

    <h4>Stergere</h4>
        <form action="editor-pannel.php" method="post">
        <input type="hidden" name="action" value="delete_article">
        <label for="id">ID articol</label><br>
        <input type="text" name="id" id="id">
        <input type="submit" value="Submit">
        </form>

</body>
</html>
