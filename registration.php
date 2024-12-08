<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="registration.css"> 
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php
        $nameErr = $emailErr = $passwordErr = "";
        $name = $email = $password = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["name"])) {
                $nameErr = "Name is required";
            } else {
                $name = test_input($_POST["name"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $nameErr = "Only letters and white space allowed";
                }
            }

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
                if (strlen($password) < 6) {
                    $passwordErr = "Password must be at least 6 characters";
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

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" id="name" name="name" placeholder='Name' value="<?php echo $name;?>">
            <span class="error"> <?php echo $nameErr;?></span>
            <br>
            <input type="text" id="email" name="email" placeholder='Email' value="<?php echo $email;?>">
            <span class="error"> <?php echo $emailErr;?></span>
            <br>
            <input type="password" id="password" name="password" placeholder='Password' value="<?php echo $password;?>">
            <span class="error"> <?php echo $passwordErr;?></span>
            <br>
            <input type="submit" name="submit" value="Register">
        </form>
        <p class='loginHere'>Already have an account? <span>Login Now</span></p>
    </div>
</body>
</html>
