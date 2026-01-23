<?php
require_once './Database.php';

if (isset($_POST['title']) || isset($_POST['contents'])) {

try {
    $pdo = Database::getInstance()->getConnection();
    echo " Connection successful!<br>";
} catch (PDOException $e) {
    die(" Connection failed: " . $e->getMessage());
}

// Example insert
try {
    $sql = "INSERT INTO articles (title, contents) 
            VALUES (:title,:contents)";
    
    $stmt = $pdo->prepare($sql);

    // Sample data
    $data = [
        'title'             => $_POST['title'] ?? 'Sample Title',
        'contents'       => $_POST['contents'] ?? 'Sample Description'
    ];

    $stmt->execute($data);

    //Redirect user to /read-stiri.php?id=lastInsertId() -- de schimbat din homepage.php
    header("Location: read-articles.php?id=" . $pdo->lastInsertId());

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
