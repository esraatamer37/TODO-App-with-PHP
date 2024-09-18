<?php
require 'config.php'; 
session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: ./home.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

   
    if ($password === $confirm_password) {
      
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username already exists!";
        } else {
       
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare ("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

        
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            setcookie('user_id', $_SESSION['user_id'], time() + 60, "/");
            setcookie('username', $_SESSION['username'], time() + 60, "/");

         
            header("Location: ./home.php");
            exit();
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
</head>
<body>
    <header>
        <h1>Register</h1>
    </header>
     
    <main>
        <div class="container">
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </div>
    </main>
</body>
</html>
