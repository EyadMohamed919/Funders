<!DOCTYPE html>
<html>
<head>
    <title>Admin — All Posts</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #333; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .yes { background: #28a745; color: white; }
        .no { background: #6c757d; color: white; }
    </style>
</head>
<body>

    <h1>Admin Dashboard — All Posts</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category ID</th>
                <th>Donee ID</th>
                <th>Featured</th>
                <th>Urgent</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr><td colspan="6" style="text-align:center;">No posts found</td></tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= $post->id ?></td>
                        <td><?= htmlspecialchars($post->title) ?></td>
                        <td><?= $post->categoryId ?></td>
                        <td><?= $post->doneeId ?></td>
                        <td><span class="badge <?= $post->featured ? 'yes' : 'no' ?>"><?= $post->featured ? 'Yes' : 'No' ?></span></td>
                        <td><span class="badge <?= $post->urgent ? 'yes' : 'no' ?>"><?= $post->urgent ? 'Yes' : 'No' ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>