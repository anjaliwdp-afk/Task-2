<?php
require_once 'database.php';

// Get all posts with usernames
$stmt = $pdo->query("
    SELECT p.*, u.username 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();

include 'includes/header.php';
?>

<h2>All Posts</h2>

<?php if(empty($posts)): ?>
    <p>No posts yet.</p>
<?php else: ?>
    <?php foreach($posts as $post): ?>
        <div class="post-card">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <div class="post-meta">
                By <?php echo htmlspecialchars($post['username']); ?> | 
                <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
            </div>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                <div class="post-actions">
                    <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>