<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="registration.css"> 
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
        <a class="logo" href="index.php">art</a>
        <nav>
        <ul>
            <li><a href="index.php"><i class="fas fa-home" style="font-size: 25px;"></i> </a></li>
            <li><a href="#"><i class="fas fa-compass" style="font-size: 25px; margin: 0px 25px"></i> </a></li>
            <li><a href="#"><i class="fas fa-bell" style="font-size: 25px;"></i> </a></li>
            <li><a href="#"><i class="fas fa-envelope" style="font-size: 25px;  margin: 0px 25px"></i> </a></li>
            <li><a href="#"><i class="fas fa-user" style="font-size: 25px;"></i> </a></li>
        </ul>
    </nav>
    </header>
    <main>
    <div class="container">
        <h2>Login</h2>
        <?php
        $emailErr = $passwordErr = "";
        $email = $password = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
            }

            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            } else {
                $password = test_input($_POST["password"]);
            }

            if (empty($emailErr) && empty($passwordErr)) {
                $servername = "localhost";
                $username = "root";
                $dbpassword = "root";

                // Create connection
                $conn = new mysqli($servername, $username, $dbpassword, "phpProject");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and execute query
                $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        // Start session and store user data
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        
                        // Redirect to dashboard or home page
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "Invalid email or password";
                    }
                } else {
                    echo "Invalid email or password";
                }

                $stmt->close();
                $conn->close();
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" id="email" name="email" placeholder='Email' value="<?php echo $email;?>">
            <span class="error"><?php echo $emailErr;?></span>
            <br>
            <input type="password" id="password" name="password" placeholder='Password'>
            <span class="error"><?php echo $passwordErr;?></span>
            <br>
            <input type="submit" name="submit" value="Login">
        </form>
        <p class='loginHere'>Don't have an account? <a href="registration.php">Register Now</a></p>
    </div>
    </main>
    <footer>
        <p>&copy; 2024 <span class='flogo'>art</span> . All rights reserved.</p>
        <p style="margin-top:10px;">
        <a href="https://arburhan.vercel.app/" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-heart animated-love"></i> AR Burhan <i class="fa-solid fa-heart animated-love endlove"></i></a>
        </p>
    </footer>
</body>
</html>