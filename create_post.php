<?php
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert post
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$_SESSION['user_id'], $title, $content])) {
            $success = 'Post created successfully!';
            // Clear form
            $title = $content = '';
        } else {
            $error = 'Failed to create post. Please try again.';
        }
    }
}

include 'includes/header.php';
?>

<h2>Create New Post</h2>

<?php if($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
    </div>
    
    <button type="submit" class="btn">Create Post</button>
    <a href="dashboard.php" class="btn">Cancel</a>
</form>

<?php include 'includes/footer.php'; ?>