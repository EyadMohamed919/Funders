<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .post-card { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; background: #f9f9f9; }
        .btn { display: inline-block; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Posts</h1>

    <?php if (empty($posts)): ?>
        <p>No posts yet.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <?= $post->displayPost() ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>