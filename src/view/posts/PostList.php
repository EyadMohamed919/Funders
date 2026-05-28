<!DOCTYPE html>
<html>
<head>
    <title>All Posts</title>
    <style>
        .post-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>All Posts</h1>

    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <?= $post->displayPost() ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>