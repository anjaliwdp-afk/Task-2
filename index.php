<?php
require_once 'database.php';

// Get latest posts
try {
    $stmt = $pdo->query("
        SELECT p.*, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC 
        LIMIT 5
    ");
    $latest_posts = $stmt->fetchAll();
} catch(PDOException $e) {
    $latest_posts = [];
}

include 'includes/header.php';
?>

<div class="hero">
    <h1>Welcome to Blog App</h1>
    <p>Share your thoughts with the world!</p>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="register.php" class="btn">Get Started</a>
    <?php endif; ?>
</div>

<h2>Latest Posts</h2>

<?php if(empty($latest_posts)): ?>
    <p>No posts yet. Be the first to create one!</p>
<?php else: ?>
    <?php foreach($latest_posts as $post): ?>
        <div class="post-card">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <div class="post-meta">
                By <?php echo htmlspecialchars($post['username']); ?> | 
                <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
            </div>
            <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...</p>
            <a href="posts.php" class="btn">Read More</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>