<!DOCTYPE html>
<html>
<head><title>Edit Post</title></head>
<body>
    <h1>Edit Post</h1>
    <form action="controller/handlers/update_post.php" method="POST">
        <input type="hidden" name="post_id" value="<?= $post->id ?>">
        <p>Title: <input type="text" name="title" value="<?= htmlspecialchars($post->title) ?>" required></p>
        <p>Category ID: <input type="number" name="category_id" value="<?= $post->categoryId ?>"></p>
        <p>Donee ID: <input type="number" name="donee_id" value="<?= $post->doneeId ?>"></p>
        <p><label><input type="checkbox" name="featured" value="1"> Mark as featured</label></p>
        <p><label><input type="checkbox" name="urgent" value="1"> Mark as Urgent</label></p>
        <button type="submit">Update</button>
    </form>
    <p><a href="../controller/post_router.php?action=show&id=<?= $post->id ?>">Cancel</a></p>
</body>
</html>