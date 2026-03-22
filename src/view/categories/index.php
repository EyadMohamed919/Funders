<?php
include("../view/header.php");
?>
<h1>Categories</h1>
<ul>
    <?php foreach ($categories as $category): ?>
        <li>
            <a href="/categories/<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a>
            <form method="post" action="/categories/<?= $category['id'] ?>/delete" style="display:inline;">
                <input type="hidden" name="router" value="deleteCategory">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<a href="/categories/create">Create New Category</a>
<?php
include("../view/footer.php");
?>
