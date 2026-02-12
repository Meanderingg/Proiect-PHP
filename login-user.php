<?php

session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once './Database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Eroare: Cerere neautorizată.");//die e alias de exit
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION = array();
            // Regerenate ID to prevent Session Fixation
            session_regenerate_id(true);
            
            $_SESSION['username'] = $user['username'];

            if(!empty($user['administrator_id'])){
                $_SESSION['admin'] = $user['administrator_id'];
                header('Location: ./homepage-admin.php');
                exit;
                }
            if(!empty($user['editor_id'])){
                $_SESSION['editor'] = $user['editor_id'];
                header('Location: ./homepage-editor.php');
                exit;
                }
            if(!empty($user['author_id'])){
                $_SESSION['author'] = $user['author_id'];
                header('Location: ./homepage-author.php');
                exit;
            }

            header('Location: ./homepage.php');
            exit;
        } else {
            $error = "Email sau parolă incorectă.";
            echo $error;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); 
        $error = "A apărut o eroare de sistem.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Login User</h1>
        <form action="login-user.php" method="POST">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <div class="mt-3 text-center">
        <p>Nu ai un cont?</p>
        <a href="create-user.php" class="btn btn-outline-secondary">Creează cont nou</a>
    </div>
</body>
</html>
