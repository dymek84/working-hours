<?php
session_start();
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = array_key_exists('message', $_POST) ? trim($_POST['message']) : null;
    $name = array_key_exists('name', $_POST) ? trim($_POST['name']) : null;
    $sql = "INSERT INTO feedback (name, message) VALUES ('$name', '$message')";
    if (mysqli_query($link, $sql)) {

        $_SESSION['test'] = 'feedback';
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    // $_SESSION['test'] = 'feedbackaaaa';
    header("location: index.php");
    exit;
}
