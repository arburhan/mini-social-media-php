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

// Handle form submission to update the user's name
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_name'])) {
    $new_name = $_POST['new_name'];
    $update_query = "UPDATE users SET name = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('si', $new_name, $user_id);
    $update_stmt->execute();
    $update_stmt->close();
}

// Fetch user information from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ART | User Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
<header>
    <a class="logo" href="index.php">art</a>
    <nav>
        <ul>
        <li>
            <a href="index.php"><i class="fas fa-home" style="font-size: 25px;"></i> </a></li>
            <li><a href="#"><i class="fas fa-compass" style="font-size: 25px; margin: 0px 25px"></i> </a></li>
            <li><a href="#"><i class="fas fa-bell" style="font-size: 25px;"></i> </a></li>
            <li><a href="#"><i class="fas fa-envelope" style="font-size: 25px;  margin: 0px 25px"></i> </a></li>
            <li><a href="#"><i class="fas fa-user" style="font-size: 25px;"></i> </a></li>
        </ul>
    </nav>
    </header>
    <main>
    <div class="profile-container">
        <h2> Welcome</h2>
        <form method="POST" action="">
            <label for="new_name">Name:</label>
            <span id="name-display"><?php echo htmlspecialchars($user['name']); ?></span>
            <i class="fas fa-edit edit-icon" id="edit-icon-name" onclick="enableEditing('name')"></i>
            <input type="text" id="name-input" name="new_name" class="input-field" required>
            <button type="submit" id="update-button-name" class="update-button">Update Name</button>
            <br>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <br>
        </form>
       
    <button onclick="window.location.href='logout.php'" style="margin-top: 20px; padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">Logout</button>
    </div>
    <script>
        function enableEditing(field) {
            var display = document.getElementById(field + '-display');
            var input = document.getElementById(field + '-input');
            var updateButton = document.getElementById('update-button-' + field);
            var editIcon = document.getElementById('edit-icon-' + field);
            
            input.value = display.textContent;
            display.style.display = 'none';
            editIcon.style.display = 'none';
            input.style.display = 'inline';
            updateButton.style.display = 'inline';
        }
    </script>
    </main>
</body>
<footer>
        <p>&copy; 2024 <span class='flogo'>art</span> . All rights reserved.</p>
        <p style="margin-top:10px;">
        <a href="https://arburhan.vercel.app/" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-heart animated-love"></i> AR Burhan <i class="fa-solid fa-heart animated-love endlove"></i></a>
        </p>
    </footer>
</html>

<?php
$stmt->close();
$conn->close();
?>
