<?php
session_start();
require_once 'config.php'; 


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$stmt = $pdo->prepare('SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);


$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        h2 {
            color:rgba(122, 3, 0.1) ;
        }
    </style>
    <title>Notes List</title>
</head>
<body>
    <header>
        <h1>Welcome to Your Notes 📝 </h1>

    </header>

    <main>
 
        <?php if ($message): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h2>✨ Your Notes ✨</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($notes) > 0): ?>
                    <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($note['title']); ?></td>
                            <td><?php echo htmlspecialchars($note['content']); ?></td>
                            <td><?php echo htmlspecialchars($note['created_at']); ?></td>
                            <td>
                                <a href="update_note.php?id=<?php echo htmlspecialchars($note['id']); ?>" class="btn btn-secondary">Edit</a>
                                <a href="delete_note.php?id=<?php echo htmlspecialchars($note['id']); ?>" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
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
    </main>
    <footer>
            <a href="add_note.php" class="btn">Add New Note</a>
            <a href="logout.php" class="btn">Logout</a>
        </nav>
    <footer>
</body>
</html>
