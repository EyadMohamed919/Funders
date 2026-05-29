<?php
class PostView{
    public static function fetchPostTable()
    { ?>
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
        <?php
    }
}