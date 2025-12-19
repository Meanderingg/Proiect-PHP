<!DOCTYPE html>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './Database.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //echo($id);

try {
    $pdo = Database::getInstance()->getConnection();

    // Fetch the record with the given ID
    $sql = "SELECT * FROM articles WHERE article_id = :id";
    $stmt = $pdo->prepare($sql);
    $data = [   'id' => $id];
    $stmt->execute($data);
    $record = $stmt->fetch();
    if (!$record) {
        die("No article found with ID " . htmlspecialchars($id));
    }
    //var_dump($record);

    // Declare variables to hold the fetched data

    $title = $record['title'];
    $contents = $record['contents'];
    $date = $record['publish_date'];

    //de adaugat autor 
    
    echo ("<h1> " . htmlspecialchars($title) . "</h1>
          <h3>" . htmlspecialchars($date) . "</h3>
          <p>" . nl2br(htmlspecialchars($contents)) . "</p>"
        );
     
} catch (PDOException $e) {
    die(" Connection failed: " . $e->getMessage());
}
}

?>

<html lang="en">
<head>
</head>
<body>
<header>
<nav>
<a href="homepage.php">Home</a> |
<a href="login-user.php">Login</a> |
<!--modify nav bar based on which user uses it-->
</nav>

</body>
<?php
?>
</html>
