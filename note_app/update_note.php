<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id) {
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));


    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ?, created_at = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $content, $date, $id, $_SESSION['user_id']]);
        
 
        $_SESSION['message'] = 'Note updated successfully.';
        header("Location: index.php");
        exit();
    } else {
        $error = "Please fill in all fields.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$note = $stmt->fetch();

if (!$note) {
    echo "Note not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Edit Note</title>
</head>
<body>
    <header>
        <h1>Edit Note</h1>
    </header>

    <main>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($note['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">content</label>
                <textarea id="content" name="content" required><?= htmlspecialchars($note['content']); ?></textarea>
            </div>
            <button type="submit" class="btn">Update Note</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </main>
    <footer>
        <na>
        <a href="index.php">Back to Notes</a>
        </na>
    <footer>
</body>
</html>
