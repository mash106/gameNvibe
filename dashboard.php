<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - gameNvibe</title>
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

        .nav-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .main-content {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 16px;
            padding: 40px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-card h1 {
            color: #3b82f6;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #94a3b8;
            font-size: 18px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }

        .action-card h3 {
            color: #3b82f6;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .action-card p {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .action-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .stat-card h3 {
            color: #3b82f6;
            margin-bottom: 10px;
        }

        .stat-card p {
            color: #94a3b8;
            line-height: 1.6;
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

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .welcome-card h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">gameNvibe</div>
        <nav class="nav-menu">
            <a href="news.php" class="nav-btn">📰 News</a>
            <a href="games.php" class="nav-btn">🎮 Games</a>
            <a href="reviews.php" class="nav-btn">⭐ Reviews</a>
            <a href="forums.php" class="nav-btn">💬 Forums</a>
            <a href="profile.php" class="nav-btn">👤 Profile</a>
        </nav>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <div class="welcome-card">
            <h1>🎮 Welcome to gameNvibe!</h1>
            <p>You're successfully logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        </div>

        <div class="quick-actions">
            <div class="action-card" onclick="location.href='news.php'">
                <h3>📰 Latest Gaming News</h3>
                <p>Stay updated with the latest gaming news, industry insights, and breaking announcements.</p>
                <a href="news.php" class="action-btn">Read News</a>
            </div>

            <div class="action-card" onclick="location.href='games.php'">
                <h3>🎮 Browse Games</h3>
                <p>Explore our comprehensive game library and discover your next favorite game.</p>
                <a href="games.php" class="action-btn">Browse Games</a>
            </div>

            <div class="action-card" onclick="location.href='reviews.php'">
                <h3>⭐ Game Reviews</h3>
                <p>Read honest reviews from the community and share your own gaming experiences.</p>
                <a href="reviews.php" class="action-btn">View Reviews</a>
            </div>

            <div class="action-card" onclick="location.href='forums.php'">
                <h3>💬 Community Forums</h3>
                <p>Join discussions with fellow gamers and participate in the gaming community.</p>
                <a href="forums.php" class="action-btn">Join Discussion</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>🎯 Your Profile</h3>
                <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?><br>
                   Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            
            <div class="stat-card">
                <h3>🛒 Shopping Cart</h3>
                <p>Items in cart: 0<br>
                   Your games library is ready to grow!</p>
            </div>
            
            <div class="stat-card">
                <h3>📊 Activity</h3>
                <p>Reviews written: 0<br>
                   Forum posts: 0<br>
                   Games purchased: 0</p>
            </div>
            
            <div class="stat-card">
                <h3>🏆 Achievements</h3>
                <p>Welcome to gameNvibe! Start exploring to unlock achievements and build your gaming profile.</p>
            </div>
        </div>
    </main>
</body>
</html>