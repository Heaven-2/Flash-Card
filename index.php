<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flash-card</title>
    <link rel="stylesheet" href="Style.css">
    <script src="Script.js"></script>
</head>

<body>
    <div class="container">
        <div class="register-card">
            <div class="card-header">
                <h2>Welcome,</h2>
                <p>Register to use the app</p>
            </div>
            <form class="register-form" method="POST" action="">
                <div class="input-group">
                    <label for="fullName">Full name</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Full-name" required>
                </div>
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder=" E-mail" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-register" name="register">Registrar</button>
            </form>
            <div class="card-footer">
                <p>Already Registerd? <a href="#" class="login-link">Login</a></p>
            </div>
        </div>
    </div>
    <div class="container1">
        <div class="register-card">
            <div class="card-header">
                <h2>Welcome back,</h2>
                <p>Login to continue</p>
            </div>
            <form class="login-form" method="POST" action="">
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email1" placeholder=" E-mail" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password1" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-register" name="Login">Login</button>
            </form>
            <div class="card-footer">
                <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
            </div>
        </div>
    </div>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->query("CREATE DATABASE IF NOT EXISTS todo");

    $conn->select_db("todo");

    $conn->query("CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullName VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS deck (
        id INT AUTO_INCREMENT PRIMARY KEY,
        deckname VARCHAR(255) NOT NULL,
        number_of_cards INT(11) NOT NULL DEFAULT 0
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS card (
        id INT AUTO_INCREMENT PRIMARY KEY,
        card_name VARCHAR(255) NOT NULL,
        answer VARCHAR(255) NOT NULL,
        deck_id INT NOT NULL,
        FOREIGN KEY (deck_id) REFERENCES deck(id) ON DELETE CASCADE
    )");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $fullName = $conn->real_escape_string($_POST['fullName']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        $sql = "INSERT INTO user (fullName, email, password) VALUES ('$fullName', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) {
        $email = $conn->real_escape_string($_POST['email1']);
        $password = $conn->real_escape_string($_POST['password1']);

        $sql = "SELECT password FROM user WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {
                header("Location: cards.php");
                exit;
            } else {
                echo "<script>alert('Invalid email or password1.');</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password2.');</script>";
        }
    }
    ?>
</body>
</html>