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
                // Here you would typically check the credentials against a database
                // For demonstration purposes, let's assume the credentials are correct
                if ($email == "user@example.com" && $password == "password") {
                    // Redirect to a different page or set session variables
                    header("Location: welcome.php");
                    exit();
                } else {
                    $loginErr = "Invalid email or password";
                }
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