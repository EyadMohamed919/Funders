<h1>Categories</h1>

<h2>Create Category</h2>
<form method="post" action="/src/router/CategoryRouter.php">
    <input type="hidden" name="router" value="createCategory">
    <input type="text" name="category_name" placeholder="Category name" required>
    <button type="submit">Create</button>
</form>

<ul>
    <?php
    if (!isset($categories) || !is_iterable($categories)) {
        $categories = [];
    }

    if (count($categories) === 0):
    ?>
        <li>No categories found.</li>
    <?php else: ?>
        <?php foreach ($categories as $category): ?>
            <li>
                <form method="post" action="/src/router/CategoryRouter.php" style="display:inline;">
                    <input type="hidden" name="router" value="updateCategory">
                    <input type="hidden" name="id" value="<?= intval($category->getId()) ?>">
                    <input type="text" name="category_name" value="<?= htmlspecialchars($category->getName()) ?>" required>
                    <button type="submit">Update</button>
                </form>
                <form method="post" action="/src/router/CategoryRouter.php" style="display:inline; margin-left: 8px;">
                    <input type="hidden" name="router" value="deleteCategory">
                    <input type="hidden" name="id" value="<?= intval($category->getId()) ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

