<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = intval($_POST['post_id']);
    $content = trim($_POST['content']);

    if (!empty($content)) {
        // Database connection
        $servername = "localhost";
        $dbUsername = "root";
        $dbPassword = "root";
        $dbname = "phpProject";

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert comment
        $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $_SESSION['user_id'], $post_id, $content);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Comment cannot be empty.";
    }
}
?>