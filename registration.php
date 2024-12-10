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
                if (strlen($password) < 8) {
                    $passwordErr = "Password must be at least 8 characters";
                }
            }

            if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
                $servername = "localhost";
                $username = "root";
                $dbPassword  = "root";

                // Create connection
                $conn = new mysqli($servername, $username, $dbPassword );

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Create database if not exists
                $createDBsql = "CREATE DATABASE IF NOT EXISTS phpProject";
                if ($conn->query($createDBsql) === TRUE) {
                    $conn->select_db("phpProject");

                    // Create table if not exists
                    $addValueonTable = "CREATE TABLE IF NOT EXISTS users (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(30) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )";

                    if ($conn->query($addValueonTable) === TRUE) {
                        // Insert user data into table
                        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $name, $email, password_hash($password, PASSWORD_DEFAULT));

                        if ($stmt->execute()) {
                            header("Location: login.php");
                            exit();
                        } else {
                            echo "Error: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        echo "Error creating table: " . $conn->error;
                    }
                } else {
                    echo "Error creating database: " . $conn->error;
                }

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
            <input type="text" id="name" name="name" placeholder='User Name' value="<?php echo $name;?>">
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
        <p class='loginHere'
        >Already have an account? <a href="login.php">Login Now</a></p>
    </div>
</body>
</html>
