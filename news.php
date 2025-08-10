<?php
session_start();

// Check if user is logged in (optional for news viewing)
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming News - gameNvibe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        .header {
            background: rgba(15, 23, 42, 0.95);
            padding: 20px;
            border-bottom: 1px solid rgba(59, 130, 246, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .logo {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .nav-btn:hover, .nav-btn.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .main-content {
            padding: 30px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: #3b82f6;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #94a3b8;
            font-size: 18px;
        }

        .news-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }

        .news-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .news-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }

        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .news-category {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .news-date {
            color: #64748b;
            font-size: 12px;
        }

        .news-card h3 {
            color: #e2e8f0;
            font-size: 20px;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .news-excerpt {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .read-more {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        .featured-news {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .featured-content h2 {
            color: #3b82f6;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .featured-content p {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .featured-image {
            background: #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 48px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .featured-news {
                grid-template-columns: 1fr;
            }

            .news-filters {
                justify-content: center;
            }

            .page-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'loginpage.html'; ?>" class="logo">gameNvibe</a>
        <nav class="nav-menu">
            <a href="news.php" class="nav-btn active">📰 News</a>
            <a href="games.php" class="nav-btn">🎮 Games</a>
            <a href="reviews.php" class="nav-btn">⭐ Reviews</a>
            <a href="forums.php" class="nav-btn">💬 Forums</a>
            <?php if ($isLoggedIn): ?>
                <a href="profile.php" class="nav-btn">👤 Profile</a>
            <?php endif; ?>
        </nav>
        <div class="user-info">
            <?php if ($isLoggedIn): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="nav-btn">Logout</a>
            <?php else: ?>
                <a href="loginpage.html" class="nav-btn">Login</a>
                <a href="signuppage.html" class="nav-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>📰 Gaming News</h1>
            <p>Stay updated with the latest gaming industry news and announcements</p>
        </div>

        <div class="news-filters">
            <button class="filter-btn active">All</button>
            <button class="filter-btn">PC Gaming</button>
            <button class="filter-btn">Console</button>
            <button class="filter-btn">Mobile</button>
            <button class="filter-btn">Industry</button>
            <button class="filter-btn">Reviews</button>
        </div>

        <div class="news-grid">
            <div class="featured-news">
                <div class="featured-content">
                    <h2>🔥 Breaking: Major Gaming Conference Announced</h2>
                    <p>The gaming industry is buzzing with excitement as the annual Gaming Expo 2025 has been officially announced. This year's event promises groundbreaking reveals, exclusive previews, and major announcements from top gaming companies worldwide.</p>
                    <a href="#" class="read-more">Read Full Story →</a>
                </div>
                <div class="featured-image">
                    🎮
                </div>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">PC Gaming</span>
                    <span class="news-date">2 hours ago</span>
                </div>
                <h3>New Graphics Cards Deliver Unprecedented Gaming Performance</h3>
                <p class="news-excerpt">Latest GPU releases are pushing the boundaries of 4K gaming, offering ray tracing capabilities that bring photorealistic graphics to mainstream gaming.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Console</span>
                    <span class="news-date">4 hours ago</span>
                </div>
                <h3>Console Wars Heat Up with New Exclusive Titles</h3>
                <p class="news-excerpt">Major console manufacturers announce exclusive partnerships with renowned game developers, promising unique gaming experiences for their platforms.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Industry</span>
                    <span class="news-date">6 hours ago</span>
                </div>
                <h3>Gaming Industry Revenue Reaches Record Highs</h3>
                <p class="news-excerpt">New market research reveals that the gaming industry continues its explosive growth, surpassing traditional entertainment sectors in revenue generation.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Mobile</span>
                    <span class="news-date">8 hours ago</span>
                </div>
                <h3>Mobile Gaming Revolution: Cloud Gaming Goes Mainstream</h3>
                <p class="news-excerpt">Cloud gaming services are making console-quality games accessible on mobile devices, revolutionizing how we think about portable gaming.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Reviews</span>
                    <span class="news-date">12 hours ago</span>
                </div>
                <h3>Indie Game of the Month: Hidden Gems Discovered</h3>
                <p class="news-excerpt">Our review team highlights outstanding indie games that deserve recognition, showcasing creativity and innovation in the gaming space.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">PC Gaming</span>
                    <span class="news-date">1 day ago</span>
                </div>
                <h3>Virtual Reality Gaming Enters New Era</h3>
                <p class="news-excerpt">Advanced VR headsets with improved resolution and haptic feedback are creating more immersive gaming experiences than ever before.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>
        </div>
    </main>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Here you would typically filter the news cards
                console.log('Filter selected:', this.textContent);
            });
        });

        // News card click handling
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('click', function() {
                // Here you would typically navigate to the full article
                console.log('News article clicked');
            });
        });
    </script>
</body>
</html>