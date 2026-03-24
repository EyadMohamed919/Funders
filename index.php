<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Empowering Change</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/index.css">
</head>
<body>

    <nav class="main-nav">
        <div class="nav-container">
            <a href="#" class="brand-logo">FUNDERS</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="browse.php">Browse</a></li>
                <li><a href="src/view/layout/Login.php" class="login-btn">Login</a></li>
            </ul>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-content">
            <h1>Support a Cause, <span class="text-gradient">Change a Life.</span></h1>
            <p>The transparent platform for medical, educational, and community fundraising.</p>
            <a href="src/view/layout/Login.php" class="cta-button">Start Exploring</a>
        </div>
    </header>

    <main class="content-wrapper" id="explore">
        <section class="section-header">
            <h2>Active Fundraisers</h2>
            <p>View the latest verified requests from our community.</p>
        </section>

        <div class="post-grid">
            <!-- <div class="post-card">
                <div class="image-container">
                    <img src="https://picsum.photos/400/250?random=1" alt="Post Image">
                    <span class="category-badge">Medical</span>
                </div>
                <div class="card-body">
                    <h3>Help Leo's Surgery</h3>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 65%;"></div>
                    </div>
                    <div class="stats-row">
                        <span><strong>$650</strong> raised</span>
                        <span>Goal: $1000</span>
                    </div>
                    <button class="view-details-btn">View Details</button>
                </div>
            </div> -->

            <?php require_once("src/view/posts/postsFeed.php") ?>
            </div>
    </main>

    <footer class="main-footer">
        <p>&copy; 2026 Funders Platform. All rights reserved.</p>
    </footer>

</body>
</html>