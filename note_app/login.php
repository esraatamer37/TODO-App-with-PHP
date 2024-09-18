<?php
session_start();
require_once 'db.php'; 


if (isset($_SESSION['user_id'])) {
    header('Location: ./home.php'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = [];

  
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

  
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ./home.php'); 
            exit();
        } else {
            $errors['login'] = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Login to Your Account</h1>
    </header>

    <main>
        <div class="login-container">
            <form action="" method="POST"> 
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>


                <?php if (!empty($errors)): ?>
                    <div class="errors">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </main>
</body>
</html>
