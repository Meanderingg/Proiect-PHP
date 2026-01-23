<?php

require_once './Database.php';
include './operatii_db.php';

session_start();

if(!isset($_SESSION['author'])){
            header('Location: ./homepage.php');
            exit;
}

if (isset($_POST['title']) && isset($_POST['contents'])) {

try {
    $pdo = Database::getInstance()->getConnection();
    //echo " Connection successful!<br>";
} catch (PDOException $e) {
    die(" Connection failed: " . $e->getMessage());
}

// Example insert
try {
    $sql = "INSERT INTO articles (title, contents, author_id) 
            VALUES (:title,:contents,:author_id)";
    
    $stmt = $pdo->prepare($sql);

    // Sample data
    $data = [
        'title'             => $_POST['title'] ?? 'Sample Title',
        'contents'       => $_POST['contents'] ?? 'Sample Description',
        'author_id'       => $_SESSION['author'] ?? '0'
    ];

    $stmt->execute($data);

    //Redirect user to /read-stiri.php?id=lastInsertId() -- de schimbat din homepage.php
    header("Location: homepage.php");
    exit;

} catch (PDOException $e) {
    echo " Insert failed: " . $e->getMessage();
}

}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Post an article</title>
</head>
<body>
    <form action="create-articles.php" method="post">
        <label>
            Title:<br>
            <input type="text" name="title" required>
        </label>
        <br>
        <label>
            Contents:<br>
            <textarea name="contents" rows="7" cols="50" required></textarea>
        </label>
        <br>

        <button type="submit">Send for review</button>
    </form>
</body>
</html>
