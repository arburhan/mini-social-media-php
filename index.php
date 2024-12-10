<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Social Media App</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/73e098e640.js" crossorigin="anonymous"></script>
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
            <article class="post">
            <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "phpProject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch posts from database
$sql = "SELECT users.name, posts.content, posts.post_date FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.post_date DESC";
$result = $conn->query($sql);

if ($result === false) {
    echo '<p>Error fetching posts: ' . htmlspecialchars($conn->error) . '</p>';
} elseif ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<article class="post" >';
        echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
        echo '<p class="post-date" style="font-size: 12px; ">' . htmlspecialchars($row["post_date"]) . '</p>';
        echo '<p>' . htmlspecialchars($row["content"]) . '</p>';
        echo '<div class="post-buttons" 
        style="padding: 10px 0;">';
        echo '<i class="fa-regular fa-heart" style="font-size: 30px; cursor: pointer;"></i>';
        echo '<i class="fa-solid fa-comment" style="font-size: 30px; margin:0 80px; cursor: pointer;"></i>';
        echo '<i class="fa-solid fa-share" style="font-size: 30px; cursor: pointer;"></i>';
        echo '</div>';
        echo '</article>';
    }
} else {
    echo '<p>No posts to display.</p>';
}

$conn->close();
?>
            </article>
            <!-- Repeat post structure for more posts -->
        </section>
    </main>
    <footer>
        <p>&copy; 2023 MySocialApp. All rights reserved.</p>
    </footer>
</body>
</html>
