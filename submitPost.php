<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Insert the post
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['user_id'], $content);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Post content cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Post</title>
</head>
<body>
    <form action="submitPost.php" method="post">
        <textarea name="content" rows="4" cols="50" placeholder="Write your post here..."></textarea><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>