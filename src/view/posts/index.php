<h1>Posts</h1>

<h2>Create Post</h2>
<form method="post" action="/src/router/PostRouter.php">
    <input type="hidden" name="router" value="createPost">
    <div>
        <input type="text" name="title" placeholder="Title" required>
    </div>
    <div>
        <textarea name="content" placeholder="Content" required></textarea>
    </div>
    <button type="submit">Create</button>
</form>

<ul>
    <?php
    if (!isset($posts) || !is_iterable($posts)) {
        $posts = [];
    }

    if (count($posts) === 0):
    ?>
        <li>No posts found.</li>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <li style="margin-bottom: 1rem;">
                <form method="post" action="/src/router/PostRouter.php" style="display:flex; gap:0.5rem; align-items:flex-start;">
                    <input type="hidden" name="router" value="updatePost">
                    <input type="hidden" name="id" value="<?= intval($post->getId()) ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($post->getTitle()) ?>" required>
                    <textarea name="content" required><?= htmlspecialchars($post->getDescription()) ?></textarea>
                    <button type="submit">Update</button>
                </form>
                <form method="post" action="/src/router/PostRouter.php" style="display:inline; margin-top: 0.25rem;">
                    <input type="hidden" name="router" value="deletePost">
                    <input type="hidden" name="id" value="<?= intval($post->getId()) ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>