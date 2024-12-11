<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbname = "phpProject";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle like/unlike functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_post_id'])) {
    $post_id = intval($_POST['like_post_id']);

    // Check if the user has already liked the post
    $check_like_sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
    $check_like_stmt = $conn->prepare($check_like_sql);
    $check_like_stmt->bind_param("ii", $user_id, $post_id);
    $check_like_stmt->execute();
    $check_like_result = $check_like_stmt->get_result();

    if ($check_like_result->num_rows === 0) {
        // Insert like into the database
        $like_sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
        $like_stmt = $conn->prepare($like_sql);
        $like_stmt->bind_param("ii", $user_id, $post_id);
        $like_stmt->execute();
    } else {
        // Remove like from the database
        $unlike_sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
        $unlike_stmt = $conn->prepare($unlike_sql);
        $unlike_stmt->bind_param("ii", $user_id, $post_id);
        $unlike_stmt->execute();
    }
    // Redirect to prevent form resubmission
    header("Location: index.php");
    exit();
}

// Handle comment functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_post_id']) && isset($_POST['comment_content'])) {
    $post_id = intval($_POST['comment_post_id']);
    $comment_content = trim($_POST['comment_content']);

    if (!empty($comment_content)) {
        // Insert comment into the database
        $comment_sql = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
        $comment_stmt = $conn->prepare($comment_sql);
        $comment_stmt->bind_param("iis", $user_id, $post_id, $comment_content);
        $comment_stmt->execute();
    }
    // Redirect to prevent form resubmission
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Social Media App</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/73e098e640.js" crossorigin="anonymous"></script>
    <script>
    function toggleCommentForm(postId) {
        var form = document.getElementById('comment-form-' + postId);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
    </script>
</head>
<body>
    <header>
        <div class="logo">MySocialApp</div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Explore</a></li>
                <li><a href="#">Notifications</a></li>
                <li><a href="#">Messages</a></li>
                <li><a href="#">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="status-update">
            <form action="submitPost.php" method="post">
                <textarea name="content" placeholder="What's happening?" required></textarea>
                <button type="submit">Post</button>
            </form>
        </section>
        <section class="feed">
            <!-- Posts will be displayed here -->
            <?php
            // Fetch posts from database
            $sql = "SELECT posts.id, users.name, posts.content, posts.post_date FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.post_date DESC";
            $result = $conn->query($sql);

            if ($result === false) {
                echo '<p>Error fetching posts: ' . htmlspecialchars($conn->error) . '</p>';
            } elseif ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<article class="post">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p class="post-date" style="font-size: 12px;">' . htmlspecialchars($row["post_date"]) . '</p>';
                    echo '<p>' . nl2br(htmlspecialchars($row["content"])) . '</p>';
                    echo '<div class="post-buttons" style="padding: 10px 0; display:flex; gap: 100px;">';

                    // Like Button Form
                    echo '<form action="" method="post" style="display: inline;">';
                    echo '<input type="hidden" name="like_post_id" value="' . $row["id"] . '">';
                    echo '<button type="submit" style="background: none; border: none; cursor: pointer;">';

                    // Check if the user has already liked this post
                    $like_check_sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
                    $like_check_stmt = $conn->prepare($like_check_sql);
                    $like_check_stmt->bind_param("ii", $user_id, $row["id"]);
                    $like_check_stmt->execute();
                    $like_check_result = $like_check_stmt->get_result();

                    if ($like_check_result->num_rows > 0) {
                        echo '<i class="fa-solid fa-heart" style="font-size: 30px; color: red;"></i>';
                    } else {
                        echo '<i class="fa-regular fa-heart" style="font-size: 30px; color:black;"></i>';
                    }

                    echo '</button>';
                    echo '</form>';

                    // Comment Button
                    echo '<button onclick="toggleCommentForm(' . $row["id"] . ')" style="background: none; border: none; cursor: pointer;">';
                    echo '<i class="fa-solid fa-comment" style="font-size: 30px;  color:black;"></i>';
                    echo '</button>';

                    // Share Button (functionality to be implemented)
                    echo '<button style="background: none; border: none; cursor: pointer;">';
                    echo '<i class="fa-solid fa-share" style="font-size: 30px; color:black;"></i>';
                    echo '</button>';

                    echo '</div>';

                    // Comment Form (hidden by default)
                    echo '<div id="comment-form-' . $row["id"] . '" style="display: none;">';
                    echo '<form action="" method="post">';
                    echo '<input type="hidden" name="comment_post_id" value="' . $row["id"] . '">';
                    echo '<textarea name="comment_content" required placeholder="Write a comment..."></textarea>';
                    echo '<button type="submit">Comment</button>';
                    echo '</form>';
                    echo '</div>';

                    // Display Comments
                    $comment_sql = "SELECT users.name, comments.content FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.comment_date ASC";
                    $comment_stmt = $conn->prepare($comment_sql);
                    $comment_stmt->bind_param("i", $row["id"]);
                    $comment_stmt->execute();
                    $comment_result = $comment_stmt->get_result();

                    if ($comment_result->num_rows > 0) {
                        echo '<div class="comments">';
                        while ($comment_row = $comment_result->fetch_assoc()) {
                            echo '<div class="comment">';
                            echo '<strong>' . htmlspecialchars($comment_row["name"]) . ':</strong> ';
                            echo '<span>' . htmlspecialchars($comment_row["content"]) . '</span>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }

                    echo '</article>';
                }
            } else {
                echo '<p>No posts to display.</p>';
            }
            ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 MySocialApp. All rights reserved.</p>
    </footer>
    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>