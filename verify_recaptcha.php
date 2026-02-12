<?php
session_start();
require_once './Mailer.php'; 

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Eroare: Sesiune invalidă.");
}

$secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFojJ4WifJWeO'; // Standard test secret
$response = $_POST['g-recaptcha-response'];
$remoteip = $_SERVER['REMOTE_ADDR'];

$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
$verify = file_get_contents($url);
$data = json_decode($verify);

if ($data->success) {
    // Sanitize inputs
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    // Initialize your Mailer class
    $mailer = new Mailer();
    
    // Prepare email content
    $subject = "Feedback nou de la " . $name;
    $body = "<h3>Mesaj nou de la $name ($email)</h3><p>$message</p>";

    // Send email using your class method
    // Sending to your specific account from config
    $mailer->sendMail('feedback@tsandulescu.daw.ssmr.ro', 'Admin Site', $subject, $body);

    header("Location: homepage.php");
} else {
    header("Location: homepage.php");
}
exit;
