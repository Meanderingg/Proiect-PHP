<?php

session_start();
require_once './Database.php';

if (!isset($_SESSION['author'])) {
    header('Location: ./homepage.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Validare CSRF eșuată.");
    }

    // Sanitize Inputs
    // trim and strip tags for the title; for contents, we keep it raw
    
    $title = trim(strip_tags($_POST['title']));
    $contents = trim($_POST['contents']);
    $author_id = $_SESSION['author'];

    if (!empty($title) && !empty($contents)) {
        try {
            $pdo = Database::getInstance()->getConnection();
            $sql = "INSERT INTO articles (title, contents, author_id)
                    VALUES (:title, :contents, :author_id)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'title'     => $title,
                'contents'  => $contents,
                'author_id' => $author_id
            ]);

            header("Location: homepage-author.php");
            exit;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = "A apărut o eroare tehnică.";
        }
    } else {
        $message = "Toate câmpurile sunt obligatorii.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Postează un articol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <h2>Adaugă Articol Nou</h2>

        <?php if($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="create-articles.php" method="post" class="mt-4">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-3">
                <label class="form-label">Titlu Articol:</label>
                <input type="text" name="title" class="form-control" required maxlength="255">
            </div>

            <div class="mb-3">
                <label class="form-label">Conținut:</label>
                <textarea name="contents" class="form-control" rows="10" required></textarea>
                <div class="form-text">Conținutul va fi verificat de un editor înainte de publicare.</div>
            </div>

            <button type="submit" class="btn btn-primary">Trimite spre verificare</button>
            <a href="homepage-author.php" class="btn btn-link">Anulează</a>
        </form>
    </div>
</body>
</html>
