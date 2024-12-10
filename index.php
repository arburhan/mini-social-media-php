<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Social Media App</title>
    <link rel="stylesheet" href="index.css">
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
                <h3>User Name</h3>
                <p>This is a sample post in the feed.</p>
                <div class="post-buttons">
                    <button class="like-button">Like</button>
                    <button class="comment-button">Comment</button>
                    <button class="share-button">Share</button>
                </div>
            </article>
            <!-- Repeat post structure for more posts -->
        </section>
    </main>
    <footer>
        <p>&copy; 2023 MySocialApp. All rights reserved.</p>
    </footer>
</body>
</html>