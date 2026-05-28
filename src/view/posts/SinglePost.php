<!DOCTYPE html>
<html>
<head>
    <title>Post Details</title>
    <style>
        .post-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        a { color: blue; }
    </style>
</head>
<body>

    <div class="post-card">
        <?= $post->displayPost() ?>
    </div>

    <p style="text-align: center;">
        <a href="post_router.php?action=index">← Back to all posts</a>
    </p>

</body>
</html>