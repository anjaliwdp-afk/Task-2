<?php
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user's posts
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$user_posts = $stmt->fetchAll();

// Get total posts count
$total_posts = count($user_posts);

include 'includes/header.php';
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

<div class="stats">
    <div class="stat-card">
        <h3>Your Posts</h3>
        <div class="stat-number"><?php echo $total_posts; ?></div>
    </div>
    <div class="stat-card">
        <h3>Member Since</h3>
        <div class="stat-number"><?php echo date('M Y'); ?></div>
    </div>
</div>

<h3>Your Recent Posts</h3>

<?php if(empty($user_posts)): ?>
    <p>You haven't created any posts yet.</p>
    <a href="create_post.php" class="btn">Create Your First Post</a>
<?php else: ?>
    <?php foreach($user_posts as $post): ?>
        <div class="post-card">
            <h4><?php echo htmlspecialchars($post['title']); ?></h4>
            <div class="post-meta"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
            <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 150))); ?>...</p>
            <div class="post-actions">
                <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>