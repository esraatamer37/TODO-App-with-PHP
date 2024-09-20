<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Your Notes</title>
</head>
<body>
    <header>
        <h1>Your Notes</h1>
        <nav>
            <a href="add_note.php" class="btn">Add New Note</a>
            <a href="logout.php" class="btn">Logout</a>
        </nav>
    </header>
    
    <main>
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($notes) > 0): ?>
                        <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?= htmlspecialchars($note['title']); ?></td>
                            <td><?= htmlspecialchars($note['content']); ?></td>
                            <td>
                                <a href="update_note.php?id=<?= htmlspecialchars($note['id']); ?>" class="btn btn-secondary">Edit</a>
                                <a href="delete_note.php?id=<?= htmlspecialchars($note['id']); ?>" class="btn btn-secondary">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No notes found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
