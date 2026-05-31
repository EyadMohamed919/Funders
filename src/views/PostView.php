<?php
require_once __DIR__ . "/../controllers/PostController.php";
require_once __DIR__ . "/../controllers/DonationController.php";
require_once __DIR__ . "/../controllers/CategoryController.php";
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

    public static function fetchAllPosts()
    {
        $postController = new PostController();
        $posts = $postController->showAllPosts();
        
        foreach($posts as $post)
        {
            $totalRaised = DonationController::getTotalAmountOfMoneyRaised($post->getId());
            echo '
                <div class="donation-card">
                    <div class="donation-content">
                        <p class="donation-category">' . CategoryController::getCategoryName($post->getCategoryId()) . '</p>
                        <h2 class="donation-title">' . $post->getTitle() . '</h2>
                        <p class="donation-details">' . $post->getDetails() . '</p>
                        
                        <div class="progress-container">
                            <div class="progress-bar" style="width: ' . ($totalRaised / $post->getTargetAmount()) * 100  . '%;"></div>
                        </div>
                        
                        <div class="donation-stats">
                            <div>Raised: <span class="amount-raised"> جنيه ' . $totalRaised . ' </span></div>
                            <div class="goal-amount">Goal: جنيه '  . $post->getTargetAmount() . '</div>
                        </div>
                    </div>
                    <a href="DonationTypePage.php?postID=' . $post->getId() . '" class="view-btn">Donate Now</a>
                </div>
    
            ';
        }
    }
}