<!DOCTYPE html>
<html>
<head><title>Create Post</title></head>
<body>
    <h1>Create New Post</h1>
    <form action="controller/handlers/create_post.php" method="POST">
        <p>Title: <input type="text" name="title" required></p>
        <p>Category ID: <input type="number" name="category_id" value="0"></p>
        <p>Donee ID: <input type="number" name="donee_id" value="0"></p>
        <button type="submit">Create</button>
    </form>
    <p><a href="controller/post_router.php?action=index">Cancel</a></p>
</body>
</html>