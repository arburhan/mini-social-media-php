<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = intval($_POST['post_id']);

    // Database connection
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "root";
    $dbname = "phpProject";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert like
    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $post_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        // Handle duplicate like attempts
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>