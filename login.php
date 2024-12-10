<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="registration.css"> 
</head>
<body>
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
                        header("Location: dashboard.php");
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
</body>
</html>