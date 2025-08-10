<?php
session_start();

// Check if user is logged in (required for forums)
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forums - gameNvibe</title>
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

        .forum-categories {
            display: grid;
            gap: 20px;
            margin-bottom: 40px;
        }

        .category-section {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .category-title {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 600;
        }

        .new-topic-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .new-topic-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .forum-list {
            display: grid;
            gap: 15px;
        }

        .forum-item {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .forum-item:hover {
            border-color: #3b82f6;
            background: rgba(30, 41, 59, 0.7);
        }

        .forum-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .forum-info {
            flex: 1;
        }

        .forum-title {
            color: #e2e8f0;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .forum-description {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.4;
        }

        .forum-stats {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .stat-item {
            color: #64748b;
            font-size: 12px;
        }

        .stat-value {
            color: #3b82f6;
            font-weight: 600;
        }

        .recent-topics {
            margin-top: 30px;
        }

        .topics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .topics-title {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 600;
        }

        .topic-item {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .topic-item:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .topic-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .topic-info {
            flex: 1;
        }

        .topic-title {
            color: #e2e8f0;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .topic-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .topic-author {
            color: #3b82f6;
            font-size: 14px;
        }

        .topic-date {
            color: #64748b;
            font-size: 12px;
        }

        .topic-category {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .topic-stats {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .topic-stat {
            text-align: center;
        }

        .topic-stat-value {
            color: #3b82f6;
            font-weight: 600;
            font-size: 16px;
        }

        .topic-stat-label {
            color: #64748b;
            font-size: 11px;
        }

        .login-prompt {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .login-prompt h3 {
            color: #3b82f6;
            margin-bottom: 15px;
        }

        .login-prompt p {
            color: #94a3b8;
            margin-bottom: 20px;
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

            .category-header {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .forum-header, .topic-header {
                flex-direction: column;
                align-items: stretch;
            }

            .forum-stats, .topic-stats {
                align-items: flex-start;
            }

            .topic-stats {
                flex-direction: row;
                gap: 15px;
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
            <a href="news.php" class="nav-btn">📰 News</a>
            <a href="games.php" class="nav-btn">🎮 Games</a>
            <a href="reviews.php" class="nav-btn">⭐ Reviews</a>
            <a href="forums.php" class="nav-btn active">💬 Forums</a>
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
            <h1>💬 Community Forums</h1>
            <p>Join the conversation with fellow gamers</p>
        </div>

        <?php if (!$isLoggedIn): ?>
            <div class="login-prompt">
                <h3>Join the Discussion!</h3>
                <p>Login or create an account to participate in our gaming community forums.</p>
                <a href="loginpage.html" class="new-topic-btn">Login to Participate</a>
            </div>
        <?php endif; ?>

        <div class="forum-categories">
            <div class="category-section">
                <div class="category-header">
                    <h2 class="category-title">🎮 General Gaming</h2>
                    <?php if ($isLoggedIn): ?>
                        <a href="#" class="new-topic-btn">+ New Topic</a>
                    <?php endif; ?>
                </div>
                <div class="forum-list">
                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Game Recommendations</div>
                                <div class="forum-description">Share and discover amazing games with the community</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">247</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">1,892</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Gaming News & Updates</div>
                                <div class="forum-description">Discuss the latest gaming news and industry updates</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">156</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">943</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Gaming Help & Support</div>
                                <div class="forum-description">Get help with technical issues and gameplay questions</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">89</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">456</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="category-section">
                <div class="category-header">
                    <h2 class="category-title">🎯 Platform Specific</h2>
                    <?php if ($isLoggedIn): ?>
                        <a href="#" class="new-topic-btn">+ New Topic</a>
                    <?php endif; ?>
                </div>
                <div class="forum-list">
                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">PC Gaming</div>
                                <div class="forum-description">Discuss PC games, hardware, and optimization tips</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">324</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">2,156</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Console Gaming</div>
                                <div class="forum-description">PlayStation, Xbox, Nintendo, and other console discussions</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">198</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">1,467</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Mobile Gaming</div>
                                <div class="forum-description">iOS, Android, and portable gaming discussions</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">145</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">892</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="recent-topics">
            <div class="topics-header">
                <h2 class="topics-title">🔥 Recent Discussions</h2>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">Best RPGs of 2025 - What are your favorites?</div>
                        <div class="topic-meta">
                            <span class="topic-author">GameMaster_Pro</span>
                            <span class="topic-date">2 hours ago</span>
                            <span class="topic-category">Game Recommendations</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">24</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">156</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">Graphics card recommendations for 4K gaming?</div>
                        <div class="topic-meta">
                            <span class="topic-author">TechGuru_88</span>
                            <span class="topic-date">5 hours ago</span>
                            <span class="topic-category">PC Gaming</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">18</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">203</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">New console exclusive announced - Thoughts?</div>
                        <div class="topic-meta">
                            <span class="topic-author">ConsoleKing</span>
                            <span class="topic-date">8 hours ago</span>
                            <span class="topic-category">Gaming News</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">31</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">287</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">Looking for co-op games to play with friends</div>
                        <div class="topic-meta">
                            <span class="topic-author">FriendlyGamer</span>
                            <span class="topic-date">1 day ago</span>
                            <span class="topic-category">Game Recommendations</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">15</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">94</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Forum item click handlers
        document.querySelectorAll('.forum-item').forEach(item => {
            item.addEventListener('click', function() {
                <?php if ($isLoggedIn): ?>
                    console.log('Navigate to forum:', this.querySelector('.forum-title').textContent);
                    // Here you would navigate to the specific forum
                <?php else: ?>
                    alert('Please login to access the forums');
                    window.location.href = 'loginpage.html';
                <?php endif; ?>
            });
        });

        // Topic item click handlers
        document.querySelectorAll('.topic-item').forEach(item => {
            item.addEventListener('click', function() {
                <?php if ($isLoggedIn): ?>
                    console.log('Navigate to topic:', this.querySelector('.topic-title').textContent);
                    // Here you would navigate to the specific topic
                <?php else: ?>
                    alert('Please login to view topics');
                    window.location.href = 'loginpage.html';
                <?php endif; ?>
            });
        });

        // New topic button handlers
        document.querySelectorAll('.new-topic-btn').forEach(btn => {
            if (btn.textContent.includes('New Topic')) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('New Topic feature coming soon!');
                });
            }
        });
    </script>
</body>
</html>