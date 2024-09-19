<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));

    if (!empty($title) && !empty($content)) {

        $stmt = $pdo->prepare("INSERT INTO notes (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $_SESSION['user_id']]);
        

        $_SESSION['message'] = 'Note added successfully.';
        header("Location: index.php");
        exit();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Add Note</title>
</head>
<body>
    <header>
        <h1>Add New Note</h1>

    </header>

    <main>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Title" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" placeholder="Content" required></textarea>
            </div>
            <button type="submit" class="btn">Add Note</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </main>
    <footer>
    <a href="index.php">Back to Notes ⁉️</a>
    </footer>
</body>
</html>
