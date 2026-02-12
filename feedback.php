<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Form</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <h2>Contactează-ne</h2>
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p style="color: green;">Mesajul a fost trimis cu succes!</p>
    <?php endif; ?>

    <form action="verify_recaptcha.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label>Nume:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Mesaj:</label><br>
        <textarea name="message" required></textarea><br><br>

        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div><br>

        <input type="submit" value="Trimite">
    </form>
</body>
</html>
