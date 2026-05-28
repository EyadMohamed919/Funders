<!DOCTYPE html>
<html>
<head><title>Create Post</title></head>
<body>
    <h1>Create New Post</h1>
    <form action="controller/handlers/create_post.php" method="POST">
        <p>Title: <input type="text" name="title" required></p>
        <p>Category ID: <input type="number" name="category_id" value=""></p>
        <p>Donee ID: <input type="number" name="donee_id" value=""></p>
       <p><label><input type="checkbox" name="featured" value="1"> Mark as featured</label></p>
        <p><label><input type="checkbox" name="urgent" value="1"> Mark as Urgent</label></p>
        <button type="submit">Create</button>
    </form>
    <p><a href="controller/post_router.php?action=index">Cancel</a></p>
</body>
</html>