<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="/public/css/CreatePostStyles.css">
<head><title>Create Post</title></head>
<body>
    <h1>Create New Post</h1>
    <form action="/src/routers/PostRouter.php" method="POST">
        <p>Title: <input type="text" name="title" required></p>
        <p>Target Amount: <input type="number" min="50" name="targetAmount" required></p>
        <p>Category: 
            <select type="number" name="category_id" value="">
                <?php
                require_once __DIR__ . "/src/controllers/CategoryController.php";
                    $categoriesArray = CategoryController::getAllCategories();
                    foreach($categoriesArray as $category)
                    {
                        echo '<option value="' . $category["category_id"] . '">' . $category["category_details"] . '</option>';
                    } 
                ?>
            </select>
            
        </p>
        <p>Post Details <input type="number" name="donee_id" hidden value="<?php 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        echo $_SESSION["UserID"]; 
        ?>
        "></p>
        <textarea name="postDetails" id="" cols="30" rows="5">
        </textarea>
       <p><label><input type="checkbox" name="featured" value="1"> Mark as featured</label></p>
        <p><label><input type="checkbox" name="urgent" value="1"> Mark as Urgent</label></p>
        <button type="submit" name="addPost">Create</button>
    </form>
    <p><a href="index.php">Cancel</a></p>
</body>
</html>