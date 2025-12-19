<!DOCTYPE html>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './Database.php';
try {
    $pdo = Database::getInstance()->getConnection();

    // Fetch all the articles
    $sql = "SELECT article_id, title FROM articles" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $record = $stmt->fetchALL(PDO::FETCH_ASSOC); //Return each row as an associative array, using column names as keys
    if (!$record) {
        die("No article found ");
    }
    //var_dump($record);

    // Declare variables to hold the fetched data

    /*
    $title = $record['title'];
    $id = $record['article_id'];

    //de facut lista cu id-urile si titlul
    
     */
    /*
    echo ("<h1> " . htmlspecialchars($title) . "</h1>
          <h3>" . htmlspecialchars($date) . "</h3>
          <p>" . nl2br(htmlspecialchars($contents)) . "</p>"
        );
     
    */
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
    <ul>
    <?php foreach ($record as $record): ?>
        <li>
            <a href="read-articles.php?id=<?= htmlspecialchars($record['article_id']) ?>">
                <?= htmlspecialchars($record['title']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
