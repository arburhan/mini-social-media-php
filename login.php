<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        $email = $password = "";
        $emailErr = $passwordErr = $loginErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            } else {
                $password = test_input($_POST["password"]);
            }

            if (empty($emailErr) && empty($passwordErr)) {
                // Database connection
                $servername = "localhost";
                $username = "root";
                $db_password = "root";
                $dbname = "phpProject";

                $conn = new mysqli($servername, $username, $db_password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        // Redirect to dashboard
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $loginErr = "Invalid email or password";
                    }
                } else {
                    $loginErr = "Invalid email or password";
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
            <span class="error"><?php echo $emailErr; ?></span>
            <input type="password" name="password" placeholder="Password" required>
            <span class="error"><?php echo $passwordErr; ?></span>
            <input type="submit" value="Login">
        </form>
        <span class="error"><?php echo $loginErr; ?></span>
        <p class='regiHere'>You have no account? <a href="registration.php" >Registration Now</a></p>
    </div>
</body>
</html>