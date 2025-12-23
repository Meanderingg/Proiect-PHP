 <?php
// Start the session
session_destroy();
//de fiecare data cand dai pe login se da logout, poate faci pagina diferita de logout?
session_start();
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

    <?php
    require_once './Database.php';

    if (isset($_POST['email']) || isset($_POST['password'])) {

    try {
        $pdo = Database::getInstance()->getConnection();
        echo " Connection successful!<br>";
    } catch (PDOException $e) {
        die(" Connection failed: " . $e->getMessage());
    }

    // Example select
    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        
        $stmt = $pdo->prepare($sql);

        // Sample data
        $data = [
            'email'    => $_POST['email']
        ];

        $stmt->execute($data);
        $user = $stmt->fetch();

        //var_dump($user);

        if ($user && password_verify($_POST['password'], $user['password'])) {

            //echo " Login successful! Welcome, " . htmlspecialchars($user['username']) . ".<br>";
            $_SESSION['username'] = $user['username'];
            if(isset($user['administrator_id'])){
                $_SESSION['admin'] = $user['administrator_id'];
                }
            //echo $_SESSION['username'] ;
            header('Location: ./homepage.php');
            exit;
        } else 
            if ($user)
            {
            echo " Invalid email or password.<br>";
            }
            else 
            {
            //echo " Invalid email or password.<br>";
            header('Location: ./create-user.php');
            exit;
            }
    } catch (PDOException $e) {
        echo " Query failed: " . $e->getMessage();
    }
    }
    ?>
