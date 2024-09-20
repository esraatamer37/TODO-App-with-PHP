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
        img {
            width: 24px; 
            height: 24px; 
        }
    </style>
   
</head>
<body>
    <header>
        <h1>Welcome to Your Notes 📝 </h1>
    </header>
    
    <main>
        <?php if ($message): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <h2><img src="https://images.emojiterra.com/google/noto-emoji/animated-emoji/2728.gif"> Your Notes <img src="https://images.emojiterra.com/google/noto-emoji/animated-emoji/2728.gif"></h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th><br></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($notes) > 0): ?>
                    <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($note['title']); ?></td>
                            <td><?php echo htmlspecialchars($note['content']); ?></td>
                            <td><br></td>
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
    </footer>
</body>
</html>
